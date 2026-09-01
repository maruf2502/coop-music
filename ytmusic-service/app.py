from fastapi import FastAPI, HTTPException
from fastapi.responses import StreamingResponse
from fastapi.middleware.cors import CORSMiddleware
from ytmusicapi import YTMusic
import time
import requests
import yt_dlp

app = FastAPI(
    title="Music YouTube Music Service",
    version="1.0.0"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global YTMusic instance (Fixes /songs/{youtube_id} NameError)
yt = YTMusic()

search_cache = {}
CACHE_TTL = 300  # 5 minutes fresh cache


@app.get("/")
def home():
    return {
        "message": "YouTube Music Service berjalan."
    }


@app.get("/clear")
def clear_all_cache():
    global search_cache
    search_cache = {}
    return {"status": "Cache Python berhasil dibersihkan."}


def get_yt_results(q: str):
    try:
        yt_client = YTMusic()
        res = yt_client.search(q)
        if res and len(res) > 0:
            return res
        return yt_client.search(q, filter="songs")
    except Exception:
        yt_client = YTMusic()
        return yt_client.search(q)


@app.get("/search")
def search(q: str):
    q_clean = q.strip().lower()
    if not q_clean:
        raise HTTPException(
            status_code=400,
            detail="Query pencarian tidak boleh kosong."
        )

    now = time.time()
    if q_clean in search_cache:
        cached_data, timestamp = search_cache[q_clean]
        if now - timestamp < CACHE_TTL:
            return {
                "query": q,
                "results": cached_data,
                "cached": True
            }

    try:
        results = get_yt_results(q)

        songs = []

        for item in results:
            # Skip items without a videoId (artist cards, album cards, playlist cards)
            video_id = item.get("videoId")
            if not video_id:
                continue

            songs.append({
                "youtube_id": video_id,

                "title": item.get("title"),

                "artist": (
                    item.get("artists", [{}])[0].get("name")
                    if item.get("artists")
                    else None
                ),

                "album": (
                    item.get("album", {}).get("name")
                    if item.get("album")
                    else None
                ),

                "thumbnail": (
                    item.get("thumbnails", [{}])[-1].get("url")
                    if item.get("thumbnails")
                    else None
                ),

                "duration": item.get("duration_seconds"),
            })

        search_cache[q_clean] = (songs, now)

        return {
            "query": q,
            "results": songs,
            "cached": False
        }

    except Exception as e:
        if q_clean in search_cache:
            return {
                "query": q,
                "results": search_cache[q_clean][0],
                "stale": True
            }
        raise HTTPException(
            status_code=500,
            detail=str(e)
        )


@app.get("/songs/{youtube_id}")
def song_detail(youtube_id: str):
    try:
        result = yt.get_song(youtube_id)

        video_details = result.get("videoDetails", {})

        return {
            "youtube_id": youtube_id,

            "title": video_details.get("title"),

            "artist": video_details.get("author"),

            "duration": (
                int(video_details["lengthSeconds"])
                if video_details.get("lengthSeconds")
                else None
            ),
        }

    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=str(e)
        )


@app.get("/stream/{youtube_id}")
def get_stream(youtube_id: str):
    # Tier 1: Direct audio stream extraction using yt-dlp by video ID
    try:
        ydl_opts = {
            'format': 'bestaudio/best',
            'quiet': True,
            'no_warnings': True,
            'extract_flat': False
        }
        url = f"https://www.youtube.com/watch?v={youtube_id}"
        with yt_dlp.YoutubeDL(ydl_opts) as ydl:
            info = ydl.extract_info(url, download=False)
            stream_url = info.get('url')
            if stream_url:
                return {
                    "youtube_id": youtube_id,
                    "stream_url": stream_url,
                    "title": info.get("title"),
                    "uploader": info.get("uploader")
                }
    except Exception as e:
        print("yt-dlp video ID extraction failed, trying metadata search fallback:", e)

    # Tier 2: Search clean title metadata with yt-dlp if specific video ID is region-restricted
    try:
        song = yt.get_song(youtube_id)
        video_details = song.get("videoDetails", {})
        title = video_details.get("title", "")
        artist = video_details.get("author", "")
        if title:
            search_query = f"ytsearch1:{title} {artist} audio"
            with yt_dlp.YoutubeDL({'format': 'bestaudio/best', 'quiet': True, 'no_warnings': True}) as ydl:
                search_info = ydl.extract_info(search_query, download=False)
                entries = search_info.get('entries', [])
                if entries and entries[0].get('url'):
                    entry = entries[0]
                    return {
                        "youtube_id": entry.get("id", youtube_id),
                        "stream_url": entry.get("url"),
                        "title": entry.get("title"),
                        "uploader": entry.get("uploader")
                    }
    except Exception as e:
        print("YTMusic title fallback error:", e)

@app.get("/audio/{youtube_id}")
def stream_audio(youtube_id: str):
    try:
        ydl_opts = {
            'format': 'bestaudio/best',
            'quiet': True,
            'no_warnings': True,
        }
        url = f"https://www.youtube.com/watch?v={youtube_id}"
        with yt_dlp.YoutubeDL(ydl_opts) as ydl:
            try:
                info = ydl.extract_info(url, download=False)
            except Exception:
                # If direct URL extraction fails, search by title metadata
                song = yt.get_song(youtube_id)
                video_details = song.get("videoDetails", {})
                title = video_details.get("title", "")
                artist = video_details.get("author", "")
                search_query = f"ytsearch1:{title} {artist} audio"
                info = ydl.extract_info(search_query, download=False)['entries'][0]

            stream_url = info.get('url')
            if not stream_url:
                raise HTTPException(status_code=404, detail="Audio stream not found")

        req = requests.get(stream_url, stream=True, headers={
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        })

        def iterfile():
            for chunk in req.iter_content(chunk_size=64*1024):
                if chunk:
                    yield chunk

        content_type = req.headers.get("Content-Type", "audio/webm")
        return StreamingResponse(
            iterfile(),
            media_type=content_type,
            headers={
                "Accept-Ranges": "bytes",
                "Content-Type": content_type,
            }
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
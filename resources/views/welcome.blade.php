<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Music - Beranda</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #121212;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* =========================
           LAYOUT
        ========================= */

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 280px;
            background: #181818;
            min-height: 100vh;
            padding: 30px 25px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .logo {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 50px;
        }

        .logo span {
            color: #9b7cff;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 15px;
            border-radius: 8px;
            color: #d0d0d0;
            font-size: 18px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #252525;
            color: #fff;
        }

        .menu a.active {
            background: #292929;
            color: #fff;
        }

        .menu-icon {
            width: 25px;
            text-align: center;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 45px 50px 80px;
        }

        .page-title {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #999;
            font-size: 17px;
            margin-bottom: 40px;
        }

        /* =========================
           SECTION
        ========================= */

        .section {
            margin-bottom: 45px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 25px;
        }

        .see-all {
            color: #aaa;
            font-size: 14px;
        }

        .see-all:hover {
            color: #fff;
        }

        /* =========================
           SONG CARD
        ========================= */

        .song-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 20px;
        }

        .song-card {
            background: #1b1b1b;
            padding: 15px;
            border-radius: 10px;
            transition: 0.2s;
        }

        .song-card:hover {
            background: #242424;
        }

        .song-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 7px;
            margin-bottom: 12px;
        }

        .song-title {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 6px;
        }

        .song-artist {
            color: #999;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* =========================
           RECENT SONG LIST
        ========================= */

        .recent-list {
            display: flex;
            flex-direction: column;
        }

        .recent-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            border-bottom: 1px solid #292929;
        }

        .recent-item:hover {
            background: #1c1c1c;
        }

        .recent-image {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 6px;
        }

        .recent-info {
            flex: 1;
            min-width: 0;
        }

        .recent-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .recent-artist {
            color: #999;
            font-size: 14px;
        }

        .recent-duration {
            color: #888;
            font-size: 14px;
        }

        /* =========================
           PLAYLIST
        ========================= */

        .playlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .playlist-card {
            background: #1b1b1b;
            border-radius: 10px;
            padding: 15px;
            transition: 0.2s;
        }

        .playlist-card:hover {
            background: #242424;
        }

        .playlist-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 14px;
        }

        .playlist-name {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .playlist-info {
            color: #999;
            font-size: 14px;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            color: #777;
            padding: 20px 0;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

            .sidebar {
                width: 75px;
                padding: 25px 10px;
            }

            .logo {
                font-size: 0;
                text-align: center;
                margin-bottom: 35px;
            }

            .logo span {
                font-size: 28px;
            }

            .menu a {
                justify-content: center;
                padding: 14px 5px;
            }

            .menu-text {
                display: none;
            }

            .content {
                margin-left: 75px;
                width: calc(100% - 75px);
                padding: 30px 25px;
            }

            .page-title {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    {{-- =========================
         SIDEBAR
    ========================== --}}

    <aside class="sidebar">

        <div class="logo">
            <span>🎵</span> Music
        </div>

        <nav class="menu">

            <a href="/" class="active">
                <span class="menu-icon">🏠</span>
                <span class="menu-text">Beranda</span>
            </a>

            <a href="/search">
                <span class="menu-icon">🔍</span>
                <span class="menu-text">Search</span>
            </a>

            <a href="/genre">
                <span class="menu-icon">🎵</span>
                <span class="menu-text">Genre</span>
            </a>

            <a href="/room">
                <span class="menu-icon">🎧</span>
                <span class="menu-text">Room</span>
            </a>

            <a href="/library">
                <span class="menu-icon">📚</span>
                <span class="menu-text">Pustaka</span>
            </a>

        </nav>

    </aside>


    {{-- =========================
         MAIN CONTENT
    ========================== --}}

    <main class="content">

        <h1 class="page-title">Beranda</h1>

        <p class="page-subtitle">
            Dengarkan musik favoritmu dan temukan lagu baru.
        </p>


        {{-- =========================
             LANJUT MENDENGARKAN
        ========================== --}}

        <section class="section">

            <div class="section-header">
                <h2 class="section-title">Lanjut Mendengarkan</h2>

                <a href="/library" class="see-all">
                    Lihat semua
                </a>
            </div>


            <div class="song-grid">

                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj"
                        alt="夜に駆ける"
                    >

                    <div class="song-title">
                        夜に駆ける
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj"
                        alt="アイドル"
                    >

                    <div class="song-title">
                        アイドル
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj"
                        alt="怪物"
                    >

                    <div class="song-title">
                        怪物
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/sSicL8DW8R3MvJHZTJXcnpVLePKSVyHRYl-GpBCOWzULQOqRut_C3nd2hpOYkpXFWE2xPIW--qY4hym1TA=w120-h120-l90-rj"
                        alt="たぶん"
                    >

                    <div class="song-title">
                        たぶん
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================
             SERING DIDENGARKAN
        ========================== --}}

        <section class="section">

            <div class="section-header">
                <h2 class="section-title">
                    Sering Didengarkan
                </h2>

                <span class="see-all">
                    Berdasarkan riwayat
                </span>
            </div>


            <div class="recent-list">

                <div class="recent-item">

                    <img
                        class="recent-image"
                        src="https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="recent-info">

                        <div class="recent-title">
                            夜に駆ける
                        </div>

                        <div class="recent-artist">
                            YOASOBI
                        </div>

                    </div>

                    <div class="recent-duration">
                        04:22
                    </div>

                </div>


                <div class="recent-item">

                    <img
                        class="recent-image"
                        src="https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="recent-info">

                        <div class="recent-title">
                            アイドル
                        </div>

                        <div class="recent-artist">
                            YOASOBI
                        </div>

                    </div>

                    <div class="recent-duration">
                        03:34
                    </div>

                </div>


                <div class="recent-item">

                    <img
                        class="recent-image"
                        src="https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="recent-info">

                        <div class="recent-title">
                            怪物
                        </div>

                        <div class="recent-artist">
                            YOASOBI
                        </div>

                    </div>

                    <div class="recent-duration">
                        03:26
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================
             PLAYLIST
        ========================== --}}

        <section class="section">

            <div class="section-header">

                <h2 class="section-title">
                    Playlist Kamu
                </h2>

                <a href="/library" class="see-all">
                    Lihat semua
                </a>

            </div>


            <div class="playlist-grid">

                <div class="playlist-card">

                    <img
                        class="playlist-cover"
                        src="https://yt3.googleusercontent.com/-smFMOtNB5Zu8EINwbq_FQUXbBp2mguY2-w0MLxcVU0-osix9gV6IF3IuIBkeWlhck8RBQ9shgKCfDw=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="playlist-name">
                        Lagu Favorit
                    </div>

                    <div class="playlist-info">
                        12 lagu
                    </div>

                </div>


                <div class="playlist-card">

                    <img
                        class="playlist-cover"
                        src="https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="playlist-name">
                        Night Playlist
                    </div>

                    <div class="playlist-info">
                        18 lagu
                    </div>

                </div>


                <div class="playlist-card">

                    <img
                        class="playlist-cover"
                        src="https://yt3.googleusercontent.com/AjWNrfzz6BqjRL5diZ-bPxFqGOsNk20xS6jcqoQWpNGWdch404mDWKVBkl4s9n74aLjXJWgldqm3Dc8=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="playlist-name">
                        Japanese Hits
                    </div>

                    <div class="playlist-info">
                        25 lagu
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================
             REKOMENDASI
        ========================== --}}

        <section class="section">

            <div class="section-header">

                <h2 class="section-title">
                    Rekomendasi Untukmu
                </h2>

                <span class="see-all">
                    Berdasarkan musikmu
                </span>

            </div>


            <div class="song-grid">

                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/wA2ZZbbrn0k6tajqlWR_t7VEi_N9KXzOf-XNxB5mmR4LAxS0BaQl_wHsQOPfFWW3OLACz3uycdZcGRs=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="song-title">
                        舞台に立って
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/dmXAMwQ9N3isr8z5sJXX2WEmA_nNN7Uqz9GCrrwt6qM0U6bLBO4oMDImxNKTWHvdZUP27WQjGdLsplKB=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="song-title">
                        会心の一撃
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/yZS5cGvyeDKPkCI5cmlD3p_O_CCjE4N6msFalbOEhuRytLta0QgzjPbJbzlh2KGzmEiG6sLafrzEf1Vx=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="song-title">
                        祝福
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>


                <div class="song-card">

                    <img
                        class="song-image"
                        src="https://yt3.googleusercontent.com/CS9aE9fPqjKPhj2VBlDPhTq15nZquoSIiT9W9AKBwnr_kkSKnTTYnHky1HmMLgtIfHzudKSlfqYU88wi=w120-h120-l90-rj"
                        alt=""
                    >

                    <div class="song-title">
                        優しい彗星
                    </div>

                    <div class="song-artist">
                        YOASOBI
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
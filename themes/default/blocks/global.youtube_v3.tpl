<!-- BEGIN: main -->
<style>
    :root {
        --primary: #fbfcfc;
        --active: #f1f1f1;
        --secondary: #767777;
        --grey: #8a8b8b;
        --b-pad: 10px;
        --s-pad: 5px;
        --bg: rgb(50, 50, 50);
    }

    a.channel {
        color: inherit;
        text-decoration: none;
    }

    a.channel:hover {
        text-decoration: underline;
    }

    .title {
        color: var(--secondary);
        font-size: 15px;
        font-weight: bold;
    }

    .sub-title {
        color: var(--grey);
        font-size: 13px;
    }

    .icon-active {
        filter: sepia(100%) hue-rotate(150deg) saturate(400%);
    }

    #playlist {
        left: 50%;
        display: flex;
        /* position: absolute;  */
        transition: all ease 0.3s;
        margin-top: 50px;
    }

    #video-dis {
        flex: 6.5;
        margin-right: 20px;
        background: black;
    }

    #video-dis iframe {
        width: 100%;
        height: 100%;
    }

    .video-li {
        flex: 3.5;
        display: flex;
        padding: var(--b-pad);
        border-radius: 3px;
        flex-direction: column;
        background: var(--primary);
    }

    .li-collapsed {
        overflow: hidden;
        height: 40px;
    }

    #vli-info {
        /* flex: 3; */
        padding: 0 var(--b-pad) 0 var(--b-pad);
    }

    #upper-info {
        display: flex;
    }

    #li-titles {
        flex: 9;
        display: flex;
        align-content: center;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }

    #li-titles div {
        padding-bottom: 5px;
    }

    #drop-icon {
        flex: 1;
        cursor: pointer;
        background: url(https://user-images.githubusercontent.com/50569315/118832584-92350500-b8e2-11eb-8398-9a90a4615b98.png) no-repeat center;
        background-size: 50%;
    }

    #lower-info {
        display: flex;
        padding-top: var(--b-pad);
    }

    #lower-info div {
        width: 40px;
        height: 40px;
        cursor: pointer;
    }

    #btn-repeat {
        margin-right: var(--b-pad);
        background: url(https://user-images.githubusercontent.com/50569315/118832591-93663200-b8e2-11eb-8b98-3b177304b555.png) no-repeat left;
        background-size: 50%;
    }

    #btn-suffle {
        margin-right: var(--b-pad);
        background: url(https://user-images.githubusercontent.com/50569315/118832597-93fec880-b8e2-11eb-9146-f978064eddb1.png) no-repeat left;
        background-size: 45%;
    }

    #btn-save {
        margin-left: auto;
        order: 2;
        right: 10px;
        margin-right: var(--b-pad);
        background: url(https://user-images.githubusercontent.com/50569315/118832594-93fec880-b8e2-11eb-8201-12cb52be231f.png) no-repeat right;
        background-size: 60%;
    }

    #vli-videos {
        flex: 7;
        overflow: auto;
    }

    .video-con {
        display: flex;
        cursor: pointer;
        padding: var(--s-pad);
        column-gap: var(--s-pad);
        margin-bottom: var(--b-pad);
    }

    .video-con:hover,
    .active-con {
        background: var(--active);
    }

    .index {
        min-width: 15px;
        align-self: center;
    }

    .thumb {
        width: 100px;
        height: 60px;
        background: var(--secondary);
    }

    .thumb img {
        width: 100%;
    }

    .v-titles {
        flex: 6;
    }

    .v-titles div:nth-child(2) {
        margin-top: var(--s-pad);
    }

    /* @media only screen and (max-width: 1150px) {
        #playlist {
            width: 95vw;
            height: 60vh;
        }
    } */

    @media only screen and (max-width: 950px) {
        /* #playlist {
            top: 10%;
            width: 50vw;
            margin: 0 auto;
            display: block;
            align-items: center;
            transform: translate(-50%, 0%);
        } */

        #video-dis {
            margin-bottom: var(--b-pad);
            width: 100%;
            height: 300px;
        }
    }

    /* @media only screen and (max-width: 800px) {
        #playlist {
            width: 60vw;
        }
    } */

    @media only screen and (max-width: 650px) {
        #playlist {
            width: 100%;
            flex-direction: column;
        }
    }
</style>

<div id="playlist">

    <div id="video-dis">
        <iframe id="display-frame" src="" title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
    </div>

    <div id="v-list" class="video-li">

        <div id="vli-info">
            <div id="upper-info">
                <div id="li-titles">
                    <div class="title">{NAME_CHANNEL}</div>
                    <div class="sub-title">
                        <a href="https://www.youtube.com/channel/{CHANNEL_ID}" class="channel"> </a>
                        -
                        <span id="video-count">1 / 2</span>
                    </div>
                </div>
                <div id="drop-icon"></div>
            </div>
            <div id="lower-info">
                <div id="btn-repeat"></div>
                <div id="btn-suffle"></div>
                <div id="btn-save"></div>
            </div>
        </div>

        <div id="vli-videos">
            <!--  active-con -->

            <!-- BEGIN: loop -->
            <div class="video-con <!-- BEGIN: one -->active-con<!-- END: one -->" video="https://www.youtube.com/embed/{ROW.videoid}">
                <div class="index title">0</div>
                <div class="thumb">
                    <img src="{ROW.thumb}" alt="">
                </div>
                <div class="v-titles">
                    <div class="title">{ROW.title}</div>
                    <div class="sub-title">
                        <a href="https://www.youtube.com/channel/{CHANNEL_ID}" class="channel"
                            target="_blank">{ROW.channel}</a>
                    </div>
                </div>
            </div>
            <!-- END: loop -->
            

        </div>

    </div>
</div>
<script>

    // utlity
    function qs(elem) {
        return document.querySelector(elem);
    }
    function qsa(elem) {
        return document.querySelectorAll(elem);
    }

    // globals
    var activeCon = 0,
        totalCons = 0;

    // elements
    const v_cons = qsa(".video-con"),
        a_cons = qsa(".active-con"),
        v_count = qs("#video-count"),
        info_btns = qsa("#lower-info div"),
        drop_icon = qs("#drop-icon"),
        video_list = qs("#v-list"),
        display = qs("#display-frame");

    // activate container
    function activate(con) {
        deactivateAll();
        indexAll();
        countVideos(con.querySelector(".index").innerHTML);
        con.classList.add("active-con");
        con.querySelector(".index").innerHTML = "►";
    }
    // deactivate all container
    function deactivateAll() {
        v_cons.forEach((container) => {
            container.classList.remove("active-con");
        });
    }
    // index containers
    function indexAll() {
        var i = 1;
        v_cons.forEach((container) => {
            container.querySelector(".index").innerHTML = i;
            i++;
        });
    }
    // update video count
    function countVideos(active) {
        v_count.innerHTML = active + " / " + v_cons.length;
    }
    // icon activate
    function toggle_icon(btn) {
        if (btn.classList.contains("icon-active")) {
            btn.classList.remove("icon-active");
        } else btn.classList.add("icon-active");
    }
    // toggle video list
    function toggle_list() {
        if (video_list.classList.contains("li-collapsed")) {
            drop_icon.style.transform = "rotate(0deg)";
            video_list.classList.remove("li-collapsed");
        } else {
            drop_icon.style.transform = "rotate(180deg)";
            video_list.classList.add("li-collapsed");
        }
    }
    function loadVideo(url) {
        display.setAttribute("src", url);
    }

    //******************
    // Main Function heres
    //******************
    window.addEventListener("load", function () {
        // starting calls
        indexAll(); // container indexes
        countVideos(1);
        activate(v_cons[0]);
        loadVideo(v_cons[0].getAttribute("video"));

        // Event handeling goes here
        // on each video container click
        v_cons.forEach((container) => {
            container.addEventListener("click", () => {
                activate(container);
                loadVideo(container.getAttribute("video"));
            });
        });
        // on each button click
        info_btns.forEach((button) => {
            button.addEventListener("click", () => {
                toggle_icon(button);
            });
        });
        // drop icon click
        drop_icon.addEventListener("click", () => {
            toggle_list();
        });
    });

</script>
<!-- END: main -->
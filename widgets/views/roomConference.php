<div id="welcome_page">
    <div id="welcome_page_header">
        <a target="_new">
            <div class="watermark leftwatermark"></div>
        </a>
        <a target="_new">
            <div class="watermark rightwatermark"></div>
        </a>
        <a class="poweredby" href="http://jitsi.org" target="_new" ><span data-i18n="poweredby"></span> jitsi.org</a>

        <div id="enter_room_container">
            <div id="enter_room_form" >
                <div id="domain_name"></div>
                <div id="enter_room">
                    <input id="enter_room_field" type="text" autofocus data-i18n="[placeholder]welcomepage.roomname" placeholder="Enter room name" />
                    <div class="icon-reload" id="reload_roomname"></div>
                    <input id="enter_room_button" type="button" data-i18n="[value]welcomepage.go" value="GO" />

                </div>
            </div>
        </div>
        <div id="brand_header"></div>
        <input type='checkbox' name='checkbox' id="disable_welcome"/>
        <label for="disable_welcome" class="disable_welcome_position" data-i18n="welcomepage.disable"></label>
        <div id="header_text">
            <!--#include virtual="plugin.header.text.html" -->
        </div>
    </div>
    <div id="welcome_page_main">
        <div id="features">
            <div class="feature_row">
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature1.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature1.content" data-i18n-options='{ "postProcess": "resolveAppName" }'>
                    </div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature2.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature2.content">
                    </div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature3.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature3.content" data-i18n-options='{ "postProcess": "resolveAppName" }'>
                    </div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature4.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature4.content">
                    </div>
                </div>
            </div>
            <div class="feature_row">
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature5.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature5.content" data-i18n-options='{ "postProcess": "resolveAppName" }'>
                    </div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature6.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature6.content" data-i18n-options='{ "postProcess": "resolveAppName" }'>
                    </div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature7.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature7.content" data-i18n-options='{ "postProcess": "resolveAppName" }'></div>
                </div>
                <div class="feature_holder">
                    <div class="feature_icon" data-i18n="welcomepage.feature8.title" ></div>
                    <div class="feature_description" data-i18n="welcomepage.feature8.content"></div>
                </div>
            </div>
        </div>
    </div>
    <!--#include virtual="plugin.welcomepage.footer.html" -->
</div>
<div id="videoconference_page">
    <div style="position: relative;" id="header_container">
        <div id="header">
            <div id="notice" class="notice" style="display: none">
                <span id="noticeText" class="noticeText"></span>
            </div>
                <span id="toolbar">
                    <span id="authentication" class="authentication" style="display: none">
                        <a class="button" id="toolbar_button_authentication" >
                            <i id="authButton" class="icon-avatar"></i>
                        </a>
                        <ul class="loginmenu">
                            <span class="loginmenuPadding"></span>
                            <li id="toolbar_auth_identity" class="identity"></li>
                            <li id="toolbar_button_login">
                                <a class="authButton" data-i18n="toolbar.login"></a>
                            </li>
                            <li id="toolbar_button_logout">
                                <a class="authButton" data-i18n="toolbar.logout"></a>
                            </li>
                        </ul>
                        <div class="header_button_separator"></div>
                    </span>
                    <a class="button" id="toolbar_button_mute" data-container="body" data-toggle="popover" data-placement="bottom" shortcut="mutePopover" data-i18n="[content]toolbar.mute" content="Mute / Unmute">
                        <i id="mute" class="icon-microphone"></i>
                    </a>
                    <div class="header_button_separator"></div>
                    <a class="button" id="toolbar_button_camera" data-container="body" data-toggle="popover" data-placement="bottom" shortcut="toggleVideoPopover" data-i18n="[content]toolbar.videomute" content="Start / stop camera">
                        <i id="video" class="icon-camera"></i>
                    </a>
                    <span id="recording" style="display: none">
                        <div class="header_button_separator"></div>
                        <a class="button" id="toolbar_button_record" data-container="body" data-toggle="popover" data-placement="bottom" data-i18n="[content]toolbar.record" content="Record">
                            <i id="recordButton" class="icon-recEnable"></i>
                        </a>
                    </span>
                    <div class="header_button_separator"></div>
                    <a class="button" id="toolbar_button_security" data-container="body" data-toggle="popover" data-placement="bottom" data-i18n="[content]toolbar.lock" content="Lock / unlock room">
                        <i id="lockIcon" class="icon-security"></i>
                    </a>
                    <div class="header_button_separator"></div>
                    <a class="button" id="toolbar_button_link" data-container="body" data-toggle="popover" data-placement="bottom" data-i18n="[content]toolbar.invite" content="Invite others">
                        <i class="icon-link"></i>
                    </a>
                    <div class="header_button_separator"></div>
                    <span class="toolbar_span">
                        <a class="button" id="toolbar_button_chat" data-container="body" data-toggle="popover" shortcut="toggleChatPopover" data-placement="bottom" data-i18n="[content]toolbar.chat" content="Open / close chat">
                            <i id="chatButton" class="icon-chat">
                                <span id="unreadMessages"></span>
                            </i>
                        </a>
                    </span>
                    <span id="prezi_button">
                        <div class="header_button_separator"></div>
                        <a class="button" id="toolbar_button_prezi" data-container="body" data-toggle="popover" data-placement="bottom" data-i18n="[content]toolbar.prezi" content="Share Prezi">
                            <i class="icon-prezi"></i>
                        </a>
                    </span>
                    <span id="etherpadButton">
                        <div class="header_button_separator"></div>
                        <a class="button" id="toolbar_button_etherpad" data-container="body" data-toggle="popover" data-placement="bottom" content="Shared document" data-i18n="[content]toolbar.etherpad">
                            <i class="icon-share-doc"></i>
                        </a>
                    </span>
                    <div class="header_button_separator"></div>
                    <span id="desktopsharing" style="display: none">
                        <a class="button" id="toolbar_button_desktopsharing" data-container="body" data-toggle="popover" data-placement="bottom" content="Share screen" data-i18n="[content]toolbar.sharescreen">
                            <i class="icon-share-desktop"></i>
                        </a>
                    </span>
                    <div class="header_button_separator"></div>
                    <a class="button" id="toolbar_button_fullScreen" data-container="body" data-toggle="popover" data-placement="bottom" content="Enter / Exit Full Screen" data-i18n="[content]toolbar.fullscreen">
                        <i id="fullScreen" class="icon-full-screen"></i>
                    </a>
                    <span id="sipCallButton" style="display: none">
                        <div class="header_button_separator"></div>
                        <a class="button" id="toolbar_button_sip" data-container="body" data-toggle="popover" data-placement="bottom" content="Call SIP number" data-i18n="[content]toolbar.sip">
                            <i class="icon-telephone"></i></a>
                    </span>
                    <span id="dialPadButton" style="display: none">
                        <div class="header_button_separator"></div>
                        <a class="button" id="toolbar_button_dialpad" data-container="body" data-toggle="popover" data-placement="bottom" content="Open dialpad" data-i18n="[content]toolbar.dialpad">
                            <i class="icon-dialpad"></i></a>
                    </span>
                    <div class="header_button_separator"></div>
                    <a class="button" id="toolbar_button_settings" data-container="body" data-toggle="popover" data-placement="bottom" content="Settings" data-i18n="[content]toolbar.Settings">
                        <i id="settingsButton" class="icon-settings"></i>
                    </a>
                    <div class="header_button_separator"></div>
                    <span id="hangup">
                        <a class="button" id="toolbar_button_hangup" data-container="body" data-toggle="popover" data-placement="bottom" content="Hang Up" data-i18n="[content]toolbar.hangup">
                            <i class="icon-hangup" style="color:#ff0000;font-size: 1.4em;"></i>
                        </a>
                    </span>
                </span>
        </div>
        <div id="subject"></div>
    </div>
    <div id="reloadPresentation"><a id="reloadPresentationLink"><i title="Reload Prezi" class="fa fa-repeat fa-lg"></i></a></div>
    <div id="videospace">
        <div id="largeVideoContainer" class="videocontainer">
            <div id="presentation"></div>
            <div id="etherpad"></div>
            <a target="_new"><div class="watermark leftwatermark"></div></a>
            <a target="_new"><div class="watermark rightwatermark"></div></a>
            <a class="poweredby" href="http://jitsi.org" target="_new" ><span data-i18n="poweredby"></span> jitsi.org</a>
            <div id="activeSpeaker">
                <img id="activeSpeakerAvatar" src=""/>
                <canvas id="activeSpeakerAudioLevel"></canvas>
            </div>
            <video id="largeVideo" autoplay oncontextmenu="return false;"></video>
        </div>
        <div id="remoteVideos">
                <span id="localVideoContainer" class="videocontainer">
                    <span id="localNick" class="nick"></span>
                    <span id="localVideoWrapper">
                        <!--<video id="localVideo" autoplay oncontextmenu="return false;" muted></video> - is now per stream generated -->
                    </span>
                    <audio id="localAudio" autoplay oncontextmenu="return false;" muted></audio>
                    <span class="focusindicator"></span>
                </span>
            <audio id="userJoined" src="sounds/joined.wav" preload="auto"></audio>
            <audio id="userLeft" src="sounds/left.wav" preload="auto"></audio>
        </div>
            <span id="bottomToolbar">
                <span class="bottomToolbar_span">
                    <a class="bottomToolbarButton" id="bottom_toolbar_chat" data-container="body" data-toggle="popover" shortcut="toggleChatPopover" data-placement="top" data-i18n="[content]bottomtoolbar.chat" content="Open / close chat">
                        <i id="chatBottomButton" class="icon-chat-simple">
                            <span id="bottomUnreadMessages"></span>
                        </i>
                    </a>
                </span>
                <div class="bottom_button_separator"></div>
                <span class="bottomToolbar_span">
                    <a class="bottomToolbarButton" id="bottom_toolbar_contact_list" data-container="body" data-toggle="popover" data-placement="top" id="contactlistpopover"  data-i18n="[content]bottomtoolbar.contactlist" content="Open / close contact list">
                        <i id="contactListButton" class="icon-contactList">
                            <span id="numberOfParticipants"></span>
                        </i>
                    </a>
                </span>
                <div class="bottom_button_separator"></div>
                <span class="bottomToolbar_span">
                    <a class="bottomToolbarButton" id="bottom_toolbar_film_strip" data-container="body" data-toggle="popover" shortcut="filmstripPopover" data-placement="top" data-i18n="[content]bottomtoolbar.filmstrip" content="Show / hide film strip">
                        <i id="filmStripButton" class="icon-filmstrip"></i>
                    </a>
                </span>
            </span>
    </div>
    <div id="chatspace" class="right-panel">
        <div id="nickname">
            <span data-i18n="chat.nickname.title"></span>
            <form>
                <input type='text' id="nickinput" data-i18n="[placeholder]chat.nickname.popover" autofocus>
            </form>
        </div>

        <!--div><i class="fa fa-comments">&nbsp;</i><span class='nick'></span>:&nbsp;<span class='chattext'></span></div-->
        <div id="chatconversation"></div>
        <audio id="chatNotification" src="sounds/incomingMessage.wav" preload="auto"></audio>
        <textarea id="usermsg" data-i18n="[placeholder]chat.messagebox" autofocus></textarea>
        <div id="smileysarea">
            <div id="smileys" id="toggle_smileys">
                <img src="images/smile.svg"/>
            </div>
        </div>
    </div>
    <div id="contactlist" class="right-panel">
        <ul>
            <li class="title"><i class="icon-contact-list"></i><span data-i18n="contactlist"></span></li>
        </ul>
    </div>
    <div id="settingsmenu" class="right-panel">
        <div class="icon-settings" data-i18n="settings.title"></div>
        <img id="avatar" src="https://www.gravatar.com/avatar/87291c37c25be69a072a4514931b1749?d=wavatar&size=30"/>
        <div class="arrow-up"></div>
        <input type="text" id="setDisplayName" data-i18n="[placeholder]settings.name" placeholder="Name">
        <input type="text" id="setEmail" placeholder="E-Mail">
        <div id = "startMutedOptions">
            <label class = "startMutedLabel">
                <input type="checkbox" id="startAudioMuted">
                <span  data-i18n="settings.startAudioMuted"></span>
            </label>
            <label class = "startMutedLabel">
                <input type="checkbox" id="startVideoMuted">
                <span data-i18n="settings.startVideoMuted"></span>
            </label>
        </div>
        <button id="updateSettings" data-i18n="settings.update"></button>
    </div>
    <a id="downloadlog" data-container="body" data-toggle="popover" data-placement="right" data-i18n="[data-content]downloadlogs" ><i class="fa fa-cloud-download"></i></a>
</div>
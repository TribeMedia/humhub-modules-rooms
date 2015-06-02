<div id="credentials">
    <p>
        Connect as: <input type="text" id="caller-id" size="15">
        <button id="connect">Connect</button>
    </p>
</div>

<div id="dialler" data-active="false">
    <p>
        Make call to:
        <input type="select" id="recipient-id">
        </input>
        <button id="dial">Call</button>
    </p>

    <hr>

    <p><strong>REMOTE:</strong></p>
    <video id="remote-video" autoplay></video>

    <hr>

    <p><strong>LOCAL:</strong></p>
    <video id="local-video" autoplay muted></video>
</div>

<hr>

<div id="messages">
</div>


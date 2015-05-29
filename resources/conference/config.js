var config = {
    hosts: {
        domain: 'tribe.tribemedia.io',
        //anonymousdomain: 'guest.example.com',
        muc: 'conference.tribe.tribemedia.io', // FIXME: use XEP-0030
        bridge: 'jitsi-videobridge.tribe.tribemedia.io', // FIXME: use XEP-0030
        //jirecon: 'jirecon.jitsi-meet.example.com',
        //call_control: 'callcontrol.jitsi-meet.example.com',
        focus: 'focus.tribe.tribemedia.io' //- defaults to 'focus.jitsi-meet.example.com'
    },
  getroomnode: function (path) { return "boogie"; },
  useStunTurn: true, // use XEP-0215 to fetch STUN and TURN server
//  useIPv6: true, // ipv6 support. use at your own risk
    useNicks: false,
    bosh: '//tribe.tribemedia.io/http-bind', // FIXME: use xep-0156 for that
    clientNode: 'https://tribe.tribemedia.io/jitsimeet', // The name of client node advertised in XEP-0115 'c' stanza
    focusUserJid: 'focus@auth.tribe.tribemedia.io', // The real JID of focus participant - can be overridden here
    //defaultSipNumber: '', // Default SIP number
    desktopSharing: 'ext', // Desktop sharing method. Can be set to 'ext', 'webrtc' or false to disable.
    chromeExtensionId: 'diibjkoicjeejcmhdnailmkgecihlobk', // Id of desktop streamer Chrome extension
    desktopSharingSources: ['screen', 'window'],
    minChromeExtVersion: '0.1', // Required version of Chrome extension
    openSctp: true, // Toggle to enable/disable SCTP channels
    disableStats: false,
    disableAudioLevels: false,
    channelLastN: -1, // The default value of the channel attribute last-n.
    adaptiveLastN: false,
    adaptiveSimulcast: false,
    useRtcpMux: true, // required for FF support
    useBundle: true, // required for FF support
    enableRecording: false,
    enableWelcomePage: false,
    enableSimulcast: false, // blocks FF support
    logStats: false, // Enable logging of PeerConnection stats via the focus
    /*noticeMessage: 'Service update is scheduled for 16th March 2015. ' +
    'During that time service will not be available. ' +
    'Apologise for inconvenience.'*/
};

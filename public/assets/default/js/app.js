if(window.location.hostname !== '127.0.0.1') {
    WebFontConfig = { google: { 
        families: [ 'Open+Sans:400,300,300italic,600,400italic:latin' ] 
    }};
    (function() {
        var wf   = document.createElement('script');
        wf.src   = '//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type  = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })(); 
}

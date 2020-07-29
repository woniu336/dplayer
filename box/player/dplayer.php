<html>
<head>
<title>Dplayer---P2P版播放器</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<meta http-equiv="content-language" content="zh-CN"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="expires" content="0"/>
<meta name="referrer" content="never"/>
<meta name="renderer" content="webkit"/>
<meta name="msapplication-tap-highlight" content="no"/>
<meta name="HandheldFriendly" content="true"/>
<meta name="x5-page-mode" content="app"/>
<meta name="Viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="/box/player/js/DPlayer.min.css" type="text/css"/>
<style type="text/css">
body,html{width:100%;height:100%;background:#000;padding:0;margin:0;overflow-x:hidden;overflow-y:hidden}
*{margin:0;border:0;padding:0;text-decoration:none}
#stats{position:fixed;top:5px;left:8px;font-size:12px;color:#fdfdfd;text-shadow:1px 1px 1px #000, 1px 1px 1px #000}
#dplayer{position:inherit}
</style>
</head>
<body style="background:#000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu=window.event.returnValue=false>
<div id="dplayer"></div>
<div id="stats"></div>
<!--兼容IE浏览器-->
<!--<script src="https://cdn.bootcss.com/babel-polyfill/7.4.4/polyfill.min.js"></script>-->
<script language="Javascript">
document.oncontextmenu=new Function("event.returnValue=false");
document.onselectstart=new Function("event.returnValue=false");
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/woniu336/dplayer@master/box/js/hls.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/woniu336/dplayer@master/box/player/js/flv.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/woniu336/dplayer@master/box/player/js/DPlayer.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/woniu336/dplayer@master/box/player/js/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/woniu336/dplayer@master/box/player/js/p2p.js"></script>
<script>
	var webdata = {
		set:function(key,val){
			window.sessionStorage.setItem(key,val);
		},
		get:function(key){
			return window.sessionStorage.getItem(key);
		},
		del:function(key){
			window.sessionStorage.removeItem(key);
		},
		clear:function(key){
			window.sessionStorage.clear();
		}
	};
	var m3u8url =  '<?php echo($_REQUEST['url']);?>'
    var dp = new DPlayer({
        autoplay: true,
        container: document.getElementById('dplayer'),
        video: {
            url: m3u8url,
        	type: 'hls',
        	pic: 'https://ae03.alicdn.com/kf/H05a9f38f74af4f9599acea038c28eea9E.jpg',
          },
          volume: 1.0,

          preload: 'auto',
          screenshot: true,
          theme: '#28FF28',
        hlsjsConfig: {
            p2pConfig: {
                logLevel: true,
                live: false,
                announce: "",
            }
        }
    });
	dp.seek(webdata.get('pay'+m3u8url));
	setInterval(function(){
		webdata.set('pay'+m3u8url,dp.video.currentTime);
	},1000);
    var _peerId = '', _peerNum = 0, _totalP2PDownloaded = 0, _totalP2PUploaded = 0;
    dp.on('stats', function (stats) {
        _totalP2PDownloaded = stats.totalP2PDownloaded;
        _totalP2PUploaded = stats.totalP2PUploaded;
        updateStats();
    });
    dp.on('peerId', function (peerId) {
        _peerId = peerId;
    });
    dp.on('peers', function (peers) {
        _peerNum = peers.length;
        updateStats();
    });
    dp.on('ended', function () {
    window.parent.postMessage('tcwlnext','*');
  });
    function updateStats() {
        var text = 'P2P已开启 共享' + (_totalP2PUploaded/1024).toFixed(2) + 'MB' + ' 已加速' + (_totalP2PDownloaded/1024).toFixed(2)
            + 'MB' + ' 此片有 ' + _peerNum + ' 位影迷正在观看';
        document.getElementById('stats').innerText = text
    }
</script>
<!--统计   -->
</body>
</html>

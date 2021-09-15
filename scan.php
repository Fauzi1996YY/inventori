<?php

include('config.php');

?><!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>Scan QR-code</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  body {
    padding: 0;
    margin: 0;
    font-family: sans-serif;
    font-size: 15px;
    line-height: 1.4em;
    }
  ul {
    list-style:none;
    padding: 0;
    }
  #preview {
    width: 100%;
    max-width: calc(100vw - 20px);
    height: calc(100vh - 300px);
    background: black;
    margin: 10px 10px 0;
    }
  #data {
    margin: 10px;
    }
    #data li {
      display: flex;
      }
    #data li .key {
      width: 20%;
      font-weight: bold;
      }
    #data li .sep {
      width: 10px;
      }
  </style>
</head>

<body>

<video id="preview"></video>
<div id="data"></div>

<script src="assets/js/vendor/instascan.min.js"></script>
<script type="text/javascript">

var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
scanner.addListener('scan',function(content){
  var val = JSON.parse(content);
  if ('kode' in val) {
    var output = '<ul>'
          +'<li><span class="key">Kode</span><span class="sep">:</span><span class="value">' + val.kode + '</span></li>'
          +'<li><span class="key">Nama</span><span class="sep">:</span><span class="value">' + val.nama + '</span></li>'
          +'<li><span class="key">Tahun</span><span class="sep">:</span><span class="value">' + val.tahun + '</span></li>'
          +'<li><span class="key">Brand</span><span class="sep">:</span><span class="value">' + val.brand + '</span></li>'
        +'</ul>'
        +'<p><a href="' + val.url + '">Edit barang ini</a></p>';
    document.getElementById('data').innerHTML = output;
  }

});

Instascan.Camera.getCameras().then(function (cameras){
  if(cameras.length>0){
    scanner.start(cameras[1]);
  }else{
    console.error('No cameras found.');
    alert('No cameras found.');
  }
}).catch(function(e){
  console.error(e);
  alert(e);
});

</script>
</body>

</html>

<?php
// ====== تنظیمات سایت (فقط همین بخش رو ویرایش کن) ======
$site = [
  'name'    => 'یلو اسکواد',
  'en'      => 'YELLOW SQUAD',
  'members' => '150+',
  'members_n' => '150',
  'bot'     => 'YellowRobot',
  'tag'     => '𝖄',
  'server'  => 'اپکس ماین',
  'mode'    => 'رول‌پلی',
  'admin'   => 'Likjphrbh',
];
$channels = [
  ['title' => 'کانال اصلی',  'user' => 'ChannelYellowSquad', 'desc' => 'اخبار، اطلاعیه‌ها و رویدادهای رسمی یلو اسکواد'],
  ['title' => 'خانواده یلو', 'user' => 'YellowFamily',       'desc' => 'فضای صمیمی اعضا، گپ و هماهنگی بازی'],
];
$owners = [
  ['name' => 'ItzRealbegaii', 'user' => 'ItzRealbegaii', 'img' => 'img/itzrealbegaii.jpg'],
  ['name' => 'JustRoham',     'user' => 'JustRoham',     'img' => 'img/justroham.jpg'],
  ['name' => 'alfred_abol',   'user' => 'alfred_abol',   'img' => 'img/alfred_abol.jpg'],
];
$contacts = [
  ['title' => 'ادمین یلو',     'desc' => 'عضویت در کلن و پشتیبانی', 'user' => 'Likjphrbh',     'img' => 'img/admin.jpg'],
  ['title' => 'ItzRealbegaii', 'desc' => 'پیام مستقیم به مالک',      'user' => 'ItzRealbegaii', 'img' => 'img/itzrealbegaii.jpg'],
  ['title' => 'JustRoham',     'desc' => 'پیام مستقیم به مالک',      'user' => 'JustRoham',     'img' => 'img/justroham.jpg'],
  ['title' => 'alfred_abol',   'desc' => 'پیام مستقیم به مالک',      'user' => 'alfred_abol',   'img' => 'img/alfred_abol.jpg'],
  ['title' => 'خانواده یلو',   'desc' => 'گپ و آشنایی با اعضا',      'user' => 'YellowFamily',  'img' => 'img/logo.svg'],
];
require __DIR__ . '/config.php';
session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'apply') {
  $_SESSION['flash'] = handle_apply();
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '#join');
  exit;
}
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$flashHtml = $flash ? '<div class="flash ' . ($flash['ok'] ? 'ok' : 'err') . '" role="status">' . e($flash['msg']) . '</div>' : '';
if ($flash && !empty($flash['copy'])) {
  $flashHtml .= '<div class="copybox"><textarea id="cp" readonly rows="11" dir="auto">' . e($flash['copy']) . '</textarea><div class="btns"><button type="button" class="btn y" id="cpb">کپی متن فرم</button><a class="btn g" href="' . e(rb('YellowRobot')) . '" target="_blank" rel="noopener">باز کردن @YellowRobot</a></div></div>';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($site['name']) ?> | YELLOW SQUAD</title>
<meta name="description" content="یلو اسکواد؛ کلن ماینکرافت بدراک با بیش از ۱۵۰ عضو فعال، ساکن سرور <?= e($site['server']) ?> در گیم‌مود <?= e($site['mode']) ?>.">
<meta name="theme-color" content="#150a33">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Vazirmatn:wght@400;600;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#150a33;--card:#2a1457;--c2:#3d2080;--y:#ffd426;--h:#ffe45c;--n:#ff4fd8;--ink:#fff;--mut:#c3b3ee;--line:rgba(255,255,255,.14);--glass:rgba(255,255,255,.06);
--fh:'Lalezar','Vazirmatn',Tahoma,sans-serif;--fb:'Vazirmatn',Tahoma,sans-serif;--fm:'Noto Sans Math','Cambria Math','Segoe UI Symbol','Apple Symbols',serif}
*{box-sizing:border-box;margin:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--fb);line-height:1.9;overflow-x:hidden}
a{color:inherit;text-decoration:none}
:focus-visible{outline:3px solid var(--y);outline-offset:3px;border-radius:8px}
.wrap{max-width:1080px;margin:0 auto;padding:0 20px}
.aur{position:fixed;inset:0;z-index:-2;overflow:hidden}
.aur i{position:absolute;border-radius:50%;filter:blur(80px);animation:dr 16s ease-in-out infinite alternate}
.aur i:nth-child(1){width:440px;height:440px;background:#ff4fd8;opacity:.4;top:-120px;right:-100px}
.aur i:nth-child(2){width:520px;height:520px;background:#6a3dff;opacity:.5;top:30%;left:-200px;animation-duration:21s}
.aur i:nth-child(3){width:340px;height:340px;background:#ffd426;opacity:.18;bottom:-100px;right:10%;animation-duration:25s}
@keyframes dr{to{transform:translate(70px,-60px) scale(1.25)}}
.cur{position:fixed;left:0;top:0;width:280px;height:280px;margin:-140px 0 0 -140px;border-radius:50%;background:radial-gradient(circle,rgba(255,79,216,.25),transparent 65%);pointer-events:none;z-index:-1;transition:transform .12s linear}

header{position:fixed;top:12px;left:0;right:0;z-index:50;padding:0 12px}
.pill{max-width:1040px;margin:0 auto;height:64px;display:flex;align-items:center;justify-content:space-between;padding:0 22px 0 10px;border-radius:999px;background:rgba(21,10,51,.8);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,79,216,.4);box-shadow:0 0 26px rgba(255,79,216,.22)}
.logo{display:flex;align-items:center;gap:10px;font:1.55rem var(--fh)}
.mark{width:36px;height:36px;border-radius:10px;background:var(--y);color:#241500;display:grid;place-items:center;font:1.35rem/1 var(--fm);transform:rotate(-6deg);box-shadow:0 0 18px var(--y)}
.burger{width:46px;height:46px;border-radius:50%;border:1px solid var(--n);background:rgba(255,79,216,.1);display:grid;place-content:center;gap:5px;cursor:pointer;padding:0;box-shadow:0 0 14px rgba(255,79,216,.4)}
.burger i{display:block;width:20px;height:2px;background:var(--y);border-radius:2px;transition:.3s}
.burger[aria-expanded=true] i:nth-child(1){transform:translateY(7px) rotate(45deg)}
.burger[aria-expanded=true] i:nth-child(2){opacity:0}
.burger[aria-expanded=true] i:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.menu{max-width:1040px;margin:10px auto 0;padding:10px;border-radius:28px;background:rgba(21,10,51,.96);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1px solid rgba(255,79,216,.35);box-shadow:0 20px 50px rgba(0,0,0,.5),0 0 30px rgba(255,79,216,.15);opacity:0;visibility:hidden;transform:translateY(-12px) scale(.98);transition:.28s}
.menu.open{opacity:1;visibility:visible;transform:none}
.menu a{display:flex;align-items:center;padding:14px 20px;border-radius:18px;color:var(--mut);font-weight:600;font-size:1.05rem;transition:.2s}
.menu a:hover,.menu a.on{background:rgba(255,79,216,.14);color:var(--h)}
.menu a.go{background:var(--y);color:#241500;margin-top:6px;justify-content:center;font-weight:800;box-shadow:0 0 20px rgba(255,212,38,.45)}

.hero{position:relative;text-align:center;padding:118px 0 56px;overflow:hidden}
.hex{position:absolute;inset:0;opacity:.2;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cg fill='none' stroke='%23a78bfa' stroke-width='1.2'%3E%3Cpath d='M28 66L0 50L0 16L28 0L56 16L56 50L28 66L28 100'/%3E%3Cpath d='M28 100L0 84L0 50M28 100L56 84L56 50'/%3E%3C/g%3E%3C/svg%3E");-webkit-mask-image:radial-gradient(ellipse at 50% 35%,#000 12%,transparent 66%);mask-image:radial-gradient(ellipse at 50% 35%,#000 12%,transparent 66%)}
.sparks span{position:absolute;bottom:-12px;width:var(--s);height:var(--s);background:var(--y);clip-path:polygon(50% 0,100% 50%,50% 100%,0 50%);opacity:0;animation:float var(--d) linear infinite;filter:drop-shadow(0 0 5px var(--y))}
@keyframes float{0%{transform:translateY(0) rotate(0);opacity:0}15%{opacity:.9}100%{transform:translateY(-92vh) rotate(200deg);opacity:0}}
.hero .wrap{position:relative}
.scene{width:200px;height:200px;margin:0 auto 18px;perspective:900px;position:relative}
.tilt{position:absolute;inset:0;transform-style:preserve-3d;transition:transform .2s ease-out}
.ring{position:absolute;left:50%;top:50%;width:232px;height:232px;margin:-116px 0 0 -116px;border-radius:50%;border:2px dashed var(--y);box-shadow:0 0 16px var(--y);animation:rg 7s linear infinite}
.ring.b{width:276px;height:276px;margin:-138px 0 0 -138px;border:2px solid var(--n);box-shadow:0 0 18px var(--n);animation:rg2 10s linear infinite}
@keyframes rg{from{transform:rotateX(74deg) rotateZ(0)}to{transform:rotateX(74deg) rotateZ(360deg)}}
@keyframes rg2{from{transform:rotateY(74deg) rotateZ(0)}to{transform:rotateY(74deg) rotateZ(-360deg)}}
.cube{position:absolute;left:50%;top:50%;width:0;height:0;transform-style:preserve-3d;animation:cb 14s linear infinite}
.cube.in{animation:cb2 9s linear infinite}
@keyframes cb{from{transform:rotateX(-22deg) rotateY(0)}to{transform:rotateX(-22deg) rotateY(360deg)}}
@keyframes cb2{from{transform:rotateX(22deg) rotateY(0)}to{transform:rotateX(22deg) rotateY(-360deg)}}
.f{--z:55px;position:absolute;width:110px;height:110px;margin:-55px 0 0 -55px;display:grid;place-items:center;border:2px solid var(--n);background:rgba(255,212,38,.1);box-shadow:0 0 18px var(--n),inset 0 0 26px rgba(255,79,216,.4);font:400 62px/1 var(--fm);color:var(--h);text-shadow:0 0 12px var(--y),0 0 28px var(--n)}
.in .f{--z:27px;width:54px;height:54px;margin:-27px 0 0 -27px;font-size:30px;background:rgba(255,212,38,.38);border-color:var(--y);box-shadow:0 0 16px var(--y)}
.f:nth-child(1){transform:translateZ(var(--z))}.f:nth-child(2){transform:rotateY(90deg) translateZ(var(--z))}.f:nth-child(3){transform:rotateY(180deg) translateZ(var(--z))}.f:nth-child(4){transform:rotateY(-90deg) translateZ(var(--z))}.f:nth-child(5){transform:rotateX(90deg) translateZ(var(--z))}.f:nth-child(6){transform:rotateX(-90deg) translateZ(var(--z))}
.hero h1{font:clamp(3.6rem,15vw,7.4rem)/1.05 var(--fh);color:var(--h);text-shadow:0 0 8px var(--h),0 0 22px var(--n),0 0 50px var(--n),0 0 90px var(--n);animation:fl 5s infinite}
@keyframes fl{0%,89%,93%,100%{opacity:1}90%,92%{opacity:.5}}
.en{direction:ltr;letter-spacing:.5em;margin-right:-.5em;font-weight:800;color:var(--n);text-shadow:0 0 12px var(--n);font-size:clamp(.85rem,3.3vw,1.15rem)}
.hero .sub{color:var(--mut);font-size:1.1rem;margin:16px auto 28px;max-width:34ch}.hero .sub b{color:#fff}
.rise>*{opacity:0}
body.ready .rise>*{animation:rise .8s both}
body.ready .rise>:nth-child(2){animation-delay:.1s}body.ready .rise>:nth-child(3){animation-delay:.2s}body.ready .rise>:nth-child(4){animation-delay:.3s}body.ready .rise>:nth-child(5){animation-delay:.4s}body.ready .rise>:nth-child(6){animation-delay:.5s}
@keyframes rise{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:none}}
.btns{display:flex;gap:14px;flex-wrap:wrap;justify-content:center}
.btn{position:relative;overflow:hidden;display:inline-block;padding:13px 28px;border-radius:14px;font-weight:800;transition:.2s}
.btn.y{background:var(--y);color:#241500;box-shadow:0 0 22px rgba(255,212,38,.5)}
.btn.y::after{content:"";position:absolute;top:0;bottom:0;width:40%;left:-60%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.8),transparent);transform:skewX(-20deg);animation:sw 3s ease-in-out infinite}
@keyframes sw{0%{left:-60%}60%,100%{left:130%}}
.btn.y:hover{transform:translateY(-3px);box-shadow:0 0 34px rgba(255,212,38,.8)}
.btn.g,.btn.w{border:1px solid var(--n);color:#fff;background:rgba(255,79,216,.1);box-shadow:0 0 14px rgba(255,79,216,.35),inset 0 0 12px rgba(255,79,216,.15)}
.btn.g:hover,.btn.w:hover{background:rgba(255,79,216,.25);transform:translateY(-3px)}
button.btn{border:0;cursor:pointer;font-family:inherit;font-size:1rem}
button.btn.g{border:1px solid var(--n)}
.stats{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-top:50px}
.stat{background:var(--glass);border:1px solid rgba(255,79,216,.35);border-radius:22px;padding:20px;box-shadow:0 0 22px rgba(255,79,216,.12)}
.stat b{display:block;font:2.4rem/1.3 var(--fh);color:var(--y);text-shadow:0 0 16px rgba(255,212,38,.6)}.stat span{color:var(--mut)}
.ticker{direction:ltr;overflow:hidden;border-block:1px solid rgba(255,79,216,.45);background:rgba(255,79,216,.08);padding:6px 0}
.ticker div{display:flex;width:max-content;animation:tk 30s linear infinite}
.ticker span{font:1.35rem var(--fh);letter-spacing:.2em;color:var(--h);text-shadow:0 0 10px var(--n);padding:0 1.3rem;white-space:nowrap}
@keyframes tk{to{transform:translateX(-50%)}}

section{padding:64px 0;scroll-margin-top:90px}
h2{font:clamp(2rem,6vw,2.8rem)/1.2 var(--fh);margin-bottom:28px;display:flex;align-items:center;gap:14px;text-shadow:0 0 18px rgba(255,79,216,.55)}
h2::before{content:"";width:10px;height:34px;border-radius:4px;background:linear-gradient(var(--y),var(--n));box-shadow:0 0 14px var(--n)}
.card{background:var(--glass);border:1px solid var(--line);border-radius:22px;padding:28px;transition:.3s}
.card:hover{border-color:var(--n);transform:translateY(-6px);box-shadow:0 18px 44px rgba(0,0,0,.4),0 0 32px rgba(255,79,216,.3)}
.bento{display:grid;gap:20px;grid-template-columns:1.4fr 1fr}
.bento p{color:#e2d9ff}.bento p+p{margin-top:12px}
.big{font:4.2rem/1 var(--fh);color:var(--y);text-shadow:0 0 18px rgba(255,212,38,.55)}
.stack{display:grid;gap:20px}
@media(max-width:760px){.bento{grid-template-columns:1fr}}
.grid{display:grid;gap:20px;grid-template-columns:repeat(auto-fit,minmax(250px,1fr))}
.ch h3,.own h3{font:1.6rem var(--fh)}
.ch .u{direction:ltr;display:block;text-align:right;color:var(--n);font-weight:600;margin:2px 0 8px}
.ch p{color:var(--mut);margin-bottom:20px}
.own{text-align:center}.own:hover{transform:none}
.own span{direction:ltr;display:block;color:var(--mut)}
.av{width:78px;height:78px;margin:0 auto 14px;border-radius:50%;overflow:hidden;border:3px solid var(--y);box-shadow:0 0 0 5px rgba(255,79,216,.3),0 0 26px rgba(255,79,216,.55);display:grid;place-items:center}
.av img{width:100%;height:100%;object-fit:cover;display:block}
.rows{display:grid;gap:14px;grid-template-columns:repeat(auto-fit,minmax(290px,1fr))}
.row{display:flex;align-items:center;gap:16px;padding:16px 18px;border-radius:18px}
.row .av{width:48px;height:48px;margin:0;box-shadow:none;border-width:2px;flex:none}
.row h3{font:1.25rem var(--fh);direction:ltr;text-align:right}
.row p{color:var(--mut);font-size:.92rem;line-height:1.5}
.row i{margin-right:auto;width:34px;height:34px;flex:none;border-radius:50%;border:1px solid var(--n);display:grid;place-items:center;color:var(--n);font-style:normal;transition:.2s}
.row:hover i{background:var(--n);color:#150a33}

.join .box{border-radius:28px;padding:44px 22px;text-align:center;background:linear-gradient(135deg,#3d2080,#2a1457);border:1px solid var(--n);box-shadow:0 0 44px rgba(255,79,216,.35),inset 0 0 40px rgba(255,79,216,.12)}
.join h2{justify-content:center;margin-bottom:12px}
.join p{max-width:520px;margin:0 auto 6px;color:#e2d9ff;font-weight:600}
.apply{display:grid;gap:14px;grid-template-columns:1fr 1fr;text-align:right;max-width:680px;margin:22px auto 0}
.apply label{display:grid;gap:6px;font-weight:600;color:#fff;font-size:.95rem}
.apply .full{grid-column:1/-1}
.apply input{font:inherit;color:#fff;background:rgba(255,255,255,.08);border:1px solid rgba(255,79,216,.45);border-radius:14px;padding:12px 14px;width:100%}
.apply input:focus{outline:none;border-color:var(--y);box-shadow:0 0 0 3px rgba(255,212,38,.25),0 0 18px rgba(255,212,38,.3)}
.tagrow{display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px dashed var(--y);border-radius:14px;padding:8px 16px;font-weight:600}
.tag{font:400 2.2rem/1.2 var(--fm);color:var(--h);text-shadow:0 0 12px var(--y),0 0 26px var(--n)}
.hp{position:absolute;left:-9999px;height:0;overflow:hidden;opacity:0}
.flash{max-width:680px;margin:18px auto 0;padding:12px 16px;border-radius:14px;font-weight:600}
.flash.ok{background:#14663a;color:#e6ffee}.flash.err{background:#7a1f3a;color:#ffe3ec}
.copybox{max-width:680px;margin:12px auto 0;text-align:center}
.copybox textarea{width:100%;font:inherit;font-size:.9rem;color:#fff;background:rgba(0,0,0,.3);border:1px solid var(--y);border-radius:14px;padding:12px;margin-bottom:12px}
.note{margin-top:16px;color:var(--mut);font-size:.95rem}.note a{color:var(--h);text-decoration:underline}
@media(max-width:600px){.apply{grid-template-columns:1fr}}
footer{border-top:1px solid var(--line);text-align:center;padding:26px 20px;color:var(--mut)}
footer b{font:400 1.3rem var(--fm);color:var(--h);text-shadow:0 0 10px var(--n)}

.loader{position:fixed;inset:0;z-index:100;display:grid;place-content:center;justify-items:center;gap:18px;background:radial-gradient(520px 380px at 50% 45%,rgba(255,79,216,.28),transparent 70%),#0b0520;transition:transform .8s cubic-bezier(.77,0,.18,1),opacity .6s .2s}
.loader.done{transform:translateY(-100%);opacity:0}
.lhex{width:130px;overflow:visible;filter:drop-shadow(0 0 20px rgba(255,79,216,.8))}
.lhex .draw{fill:none;stroke:var(--y);stroke-width:5;stroke-linejoin:round;stroke-dasharray:560;stroke-dashoffset:560;animation:draw 1.4s ease forwards}
.lhex text{fill:var(--h);opacity:0;animation:fade .6s .9s forwards}
@keyframes draw{to{stroke-dashoffset:0}}@keyframes fade{to{opacity:1}}
.lt{direction:ltr;font:1.5rem var(--fh);color:#fff;text-shadow:0 0 14px var(--n);animation:track 1.8s ease both}
@keyframes track{from{letter-spacing:.1em;opacity:0}to{letter-spacing:.5em;opacity:1}}
.lbar{width:min(260px,64vw);height:4px;border-radius:4px;background:rgba(255,255,255,.14);overflow:hidden}
.lbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--y),var(--n));box-shadow:0 0 14px var(--n)}
.lpct{direction:ltr;color:var(--mut);font-size:.95rem}
body.load{overflow:hidden}
.rv{opacity:0;transform:translateY(26px);transition:opacity .7s,transform .7s}
.rv.in{opacity:1;transform:none}
.card.rv.in:hover{transform:translateY(-6px)}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.rise>*,.rv{opacity:1!important;transform:none!important}.sparks,.cur{display:none}html{scroll-behavior:auto}}
</style>
</head>
<body class="load">
<div class="aur" aria-hidden="true"><i></i><i></i><i></i></div>
<div class="cur" id="cur" aria-hidden="true"></div>
<noscript><style>#loader{display:none}.rise>*{opacity:1}body.load{overflow:auto}</style></noscript>
<div class="loader" id="loader" role="status" aria-label="در حال بارگذاری">
  <svg class="lhex" viewBox="0 0 200 200" aria-hidden="true"><polygon class="draw" points="100,10 177.9,55 177.9,145 100,190 22.1,145 22.1,55"/><text x="100" y="124" text-anchor="middle" font-family="'Noto Sans Math','Cambria Math','Segoe UI Symbol',serif" font-size="78"><?= e($site['tag']) ?></text></svg>
  <div class="lt"><?= e($site['en']) ?></div>
  <div class="lbar"><i id="lbar"></i></div>
  <div class="lpct" id="lpct">0%</div>
</div>
<header>
  <div class="pill">
    <a class="logo" href="#top"><span class="mark"><?= e($site['tag']) ?></span><?= e($site['name']) ?></a>
    <button class="burger" id="burger" type="button" aria-label="منو" aria-expanded="false" aria-controls="menu"><i></i><i></i><i></i></button>
  </div>
  <nav class="menu" id="menu" aria-label="منوی اصلی">
    <a href="#top">خانه</a>
    <a href="#about">آشنایی با ما</a>
    <a href="#channels">کانال‌ها</a>
    <a href="#owners">مالکان</a>
    <a href="#contact">ارتباط با ما</a>
    <a class="go" href="#join">ورود به مجموعه</a>
  </nav>
</header>

<main id="top">
  <div class="hero">
    <div class="hex" aria-hidden="true"></div>
    <div class="sparks" id="sparks" aria-hidden="true"></div>
    <div class="wrap">
      <div class="rise">
        <div class="scene" aria-hidden="true"><div class="tilt"><i class="ring"></i><i class="ring b"></i><div class="cube"><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b></div><div class="cube in"><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b><b class="f">𝖄</b></div></div></div>
        <h1><?= e($site['name']) ?></h1>
        <div class="en"><?= e($site['en']) ?></div>
        <p class="sub">کلن ماینکرافت بدراک؛ <b><?= e($site['members']) ?></b> عضو فعال، ساکن سرور <?= e($site['server']) ?></p>
        <div class="btns">
          <a class="btn y" href="#join">ورود به مجموعه</a>
          <a class="btn g" href="#about">آشنایی با ما</a>
        </div>
      </div>
      <div class="stats rise">
        <div class="stat"><b data-count="<?= e($site['members_n']) ?>"><?= e($site['members']) ?></b><span>اعضای فعال</span></div>
        <div class="stat"><b><?= e($site['server']) ?></b><span>سرور</span></div>
        <div class="stat"><b><?= e($site['mode']) ?></b><span>گیم‌مود</span></div>
      </div>
    </div>
  </div>

  <div class="ticker" aria-hidden="true"><div></div></div>

  <section id="about">
    <div class="wrap">
      <h2>آشنایی با ما</h2>
      <div class="bento">
        <div class="card">
          <p>یلو اسکواد یک کلن ماینکرافت بدراک است؛ جمعی از بازیکنان فعال که کنار هم می‌سازند، می‌جنگند و داستان می‌سازند.</p>
          <p>فعالیت ما روی سرور <b><?= e($site['server']) ?></b> و در گیم‌مود <b><?= e($site['mode']) ?></b> شروع شده؛ جایی که هر عضو یک نقش و یک داستان دارد.</p>
          <p>اگر دنبال هم‌تیمی‌های فعال و خوش‌اخلاق می‌گردی، جای تو همین‌جاست.</p>
        </div>
        <div class="stack">
          <div class="card"><div class="big" data-count="<?= e($site['members_n']) ?>"><?= e($site['members']) ?></div>عضو فعال در کلن</div>
          <div class="card"><div class="big" style="font-size:2.6rem"><?= e($site['server']) ?></div>سرور اصلی ما، گیم‌مود <?= e($site['mode']) ?></div>
        </div>
      </div>
    </div>
  </section>

  <section id="channels">
    <div class="wrap">
      <h2>کانال‌های ما</h2>
      <div class="grid">
        <?php foreach ($channels as $c): ?>
        <div class="card ch">
          <h3><?= e($c['title']) ?></h3>
          <span class="u">@<?= e($c['user']) ?></span>
          <p><?= e($c['desc']) ?></p>
          <a class="btn y" href="<?= rb($c['user']) ?>" target="_blank" rel="noopener">عضویت در روبیکا</a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="owners">
    <div class="wrap">
      <h2>مالکان</h2>
      <div class="grid">
        <?php foreach ($owners as $o): ?>
        <div class="card own">
          <div class="av"><img src="<?= e($o['img']) ?>" alt="<?= e($o['name']) ?>" loading="lazy"></div>
          <h3><?= e($o['name']) ?></h3>
          <span>@<?= e($o['user']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="contact">
    <div class="wrap">
      <h2>ارتباط با ما</h2>
      <div class="rows">
        <?php foreach ($contacts as $k): ?>
        <a class="card row" href="<?= rb($k['user']) ?>" target="_blank" rel="noopener">
          <div class="av"><img src="<?= e($k['img']) ?>" alt="" loading="lazy"></div>
          <div><h3><?= e($k['title']) ?></h3><p><?= e($k['desc']) ?></p></div>
          <i aria-hidden="true">‹</i>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="join" id="join">
    <div class="wrap">
      <div class="box">
        <h2>ورود به مجموعه</h2>
        <p>فرم ثبت‌نام کلن <?= e($site['en']) ?> رو پر کن؛ درخواستت برای مالکان و ادمین ثبت می‌شه و بعد از بررسی از روبیکا بهت پیام می‌دیم.</p>
        <?= $flashHtml ?>
        <form class="apply" method="post" action="#join" autocomplete="off">
          <input type="hidden" name="do" value="apply">
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <div class="hp" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
          <label>Name · اسم<input name="name" required maxlength="40"></label>
          <label>Age · سن<input name="age" type="number" inputmode="numeric" min="8" max="80" required></label>
          <label>UserName · یوزرنیم روبیکا<input name="username" required maxlength="80" dir="ltr" placeholder="@username"></label>
          <label>ID · آیدی<input name="gid" required maxlength="40" dir="ltr"></label>
          <div class="full tagrow"><span>Tag · تگ کلن</span><b class="tag"><?= e($site['tag']) ?></b></div>
          <div class="btns full">
            <button class="btn y" type="submit">ارسال فرم ثبت‌نام</button>
            <a class="btn g" href="<?= rb($site['admin']) ?>" target="_blank" rel="noopener">پیام مستقیم به ادمین</a>
          </div>
        </form>
        <p class="note">می‌تونی فرم رو مستقیم تو ربات <a href="<?= rb($site['bot']) ?>" target="_blank" rel="noopener">@<?= e($site['bot']) ?></a> هم بفرستی.</p>
      </div>
    </div>
  </section>
</main>

<footer><b><?= e($site['tag']) ?></b> © <?= date('Y') ?> <?= e($site['name']) ?> · ادمین: @<?= e($site['admin']) ?></footer>
<script>
(function(){
  var b=document.body,L=document.getElementById('loader'),bar=document.getElementById('lbar'),pct=document.getElementById('lpct');
  var rm=matchMedia('(prefers-reduced-motion: reduce)').matches,dur=rm?300:2600,t0=null;
  function finish(){L.classList.add('done');b.classList.remove('load');b.classList.add('ready');document.dispatchEvent(new Event('siteready'));setTimeout(function(){L.remove()},1000)}
  function step(t){if(!t0)t0=t;var p=Math.min((t-t0)/dur,1),v=1-Math.pow(1-p,3);bar.style.width=(v*100)+'%';pct.textContent=Math.round(v*100)+'%';p<1?requestAnimationFrame(step):finish()}
  requestAnimationFrame(step);

  var btn=document.getElementById('burger'),menu=document.getElementById('menu');
  function set(o){menu.classList.toggle('open',o);btn.setAttribute('aria-expanded',o)}
  btn.addEventListener('click',function(e){e.stopPropagation();set(!menu.classList.contains('open'))});
  menu.addEventListener('click',function(e){if(e.target.closest('a'))set(false)});
  document.addEventListener('click',function(e){if(!e.target.closest('header'))set(false)});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'){set(false);btn.focus()}});

  var links=[].slice.call(menu.querySelectorAll('a:not(.go)'));
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){es.forEach(function(x){if(x.isIntersecting){links.forEach(function(a){a.classList.toggle('on',a.getAttribute('href')==='#'+x.target.id)})}})},{rootMargin:'-45% 0px -50% 0px'});
    ['top','about','channels','owners','contact'].forEach(function(id){var el=document.getElementById(id);if(el)io.observe(el)});
  }

  var cs=[].slice.call(document.querySelectorAll('[data-count]')),ready=false;
  function run(el){var n=+el.dataset.count,suf=el.textContent.replace(/[0-9]/g,''),t0=null;if(rm)return;function f(t){if(!t0)t0=t;var p=Math.min((t-t0)/1600,1),v=1-Math.pow(1-p,3);el.textContent=Math.round(n*v)+suf;if(p<1)requestAnimationFrame(f)}requestAnimationFrame(f)}
  if('IntersectionObserver' in window){var io2=new IntersectionObserver(function(es){es.forEach(function(x){if(x.isIntersecting&&ready){io2.unobserve(x.target);run(x.target)}})},{threshold:.4});cs.forEach(function(c){io2.observe(c)});document.addEventListener('siteready',function(){ready=true;cs.forEach(function(c){io2.unobserve(c);io2.observe(c)})})}

  var sp=document.getElementById('sparks');
  for(var i=0;i<20;i++){var s=document.createElement('span');s.style.cssText='left:'+(Math.random()*100)+'%;--s:'+(6+Math.random()*10)+'px;--d:'+(9+Math.random()*10)+'s;animation-delay:-'+(Math.random()*12)+'s';if(i%3==0)s.style.background='#ff4fd8';sp.appendChild(s)}

  var hero=document.querySelector('.hero'),tl=document.querySelector('.tilt');
  if(hero&&tl&&!rm){hero.addEventListener('pointermove',function(e){var r=hero.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;tl.style.transform='rotateY('+(x*45)+'deg) rotateX('+(-y*35)+'deg)'});hero.addEventListener('pointerleave',function(){tl.style.transform=''})}
  var cur=document.getElementById('cur');
  if(cur&&!rm)addEventListener('pointermove',function(e){cur.style.transform='translate('+e.clientX+'px,'+e.clientY+'px)'});

  var tk=document.querySelector('.ticker div');
  if(tk)tk.innerHTML=new Array(13).join('<span>YELLOW SQUAD ✦ 𝖄 ✦</span>');

  var rv=[].slice.call(document.querySelectorAll('section h2,section .card,.join .box'));
  rv.forEach(function(el){el.classList.add('rv')});
  if('IntersectionObserver' in window){var io3=new IntersectionObserver(function(es){es.forEach(function(x){if(x.isIntersecting){x.target.classList.add('in');io3.unobserve(x.target)}})},{threshold:.12});rv.forEach(function(el){io3.observe(el)})}
  else rv.forEach(function(el){el.classList.add('in')});

  var cb=document.getElementById('cpb');
  if(cb)cb.addEventListener('click',function(){var ta=document.getElementById('cp');ta.select();try{navigator.clipboard.writeText(ta.value)}catch(x){document.execCommand('copy')}cb.textContent='کپی شد'});
})();
</script>
</body>
</html>

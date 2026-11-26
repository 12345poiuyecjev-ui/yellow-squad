<?php
require __DIR__ . '/config.php';
session_name('ysadmin');
session_start();
header('X-Robots-Tag: noindex');
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
if (isset($_GET['out'])) { $_SESSION = []; session_destroy(); header('Location: admin.php'); exit; }

$err = '';
if (empty($_SESSION['admin']) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'login') {
  $u = trim((string)($_POST['u'] ?? '')); $p = (string)($_POST['p'] ?? '');
  if (($_SESSION['lock'] ?? 0) > time()) {
    $err = 'چند بار اشتباه زدی؛ چند دقیقه بعد دوباره امتحان کن.';
  } elseif (isset($admins[$u]) && hash_equals($admins[$u], $p)) {
    session_regenerate_id(true);
    $_SESSION['admin'] = $u; $_SESSION['csrf'] = bin2hex(random_bytes(16));
    header('Location: admin.php'); exit;
  } else {
    usleep(600000);
    $_SESSION['fails'] = ($_SESSION['fails'] ?? 0) + 1;
    if ($_SESSION['fails'] >= 5) { $_SESSION['lock'] = time() + 300; $_SESSION['fails'] = 0; }
    $err = 'نام کاربری یا رمز اشتباهه.';
  }
}
$me = $_SESSION['admin'] ?? null;
$csrf = $_SESSION['csrf'];

if ($me && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
    $id = (string)($_POST['id'] ?? ''); $do = (string)($_POST['do'] ?? ''); $st = (string)($_POST['st'] ?? '');
    if ($do === 'status' && in_array($st, ['new', 'accepted', 'rejected'], true)) {
      apps_update(function($rows) use ($id, $st, $me){
        foreach ($rows as &$r) { if (($r['id'] ?? '') === $id) { $r['status'] = $st; $r['by'] = $me; } }
        unset($r); return $rows;
      });
    } elseif ($do === 'delete') {
      apps_update(function($rows) use ($id){
        return array_values(array_filter($rows, function($r) use ($id){ return ($r['id'] ?? '') !== $id; }));
      });
    }
  }
  header('Location: ' . $_SERVER['REQUEST_URI']); exit;
}

$labels = ['new' => 'جدید', 'accepted' => 'تایید شده', 'rejected' => 'رد شده'];
$all = $me ? array_reverse(apps_load()) : [];

if ($me && isset($_GET['csv'])) {
  $safe = function($v){ $v = (string)$v; return preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v; };
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="applications.csv"');
  echo "\xEF\xBB\xBF";
  $o = fopen('php://output', 'w');
  fputcsv($o, ['time', 'name', 'age', 'username', 'id', 'tag', 'status']);
  foreach ($all as $r) fputcsv($o, [$r['time'] ?? '', $safe($r['name'] ?? ''), $r['age'] ?? '', $safe($r['username'] ?? $r['rubika'] ?? ''), $safe($r['gid'] ?? ''), $r['tag'] ?? '', $labels[$r['status'] ?? 'new'] ?? '']);
  exit;
}
$f = $_GET['f'] ?? 'all';
$cnt = ['all' => count($all), 'new' => 0, 'accepted' => 0, 'rejected' => 0];
foreach ($all as $r) { $s = $r['status'] ?? 'new'; if (isset($cnt[$s])) $cnt[$s]++; }
$rows = $f === 'all' ? $all : array_values(array_filter($all, function($r) use ($f){ return ($r['status'] ?? 'new') === $f; }));
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>پنل یلو اسکواد</title>
<link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Vazirmatn:wght@400;600;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0}
body{font-family:'Vazirmatn',Tahoma,sans-serif;background:#f3efff;color:#1a0b3d;line-height:1.8}
a{color:inherit;text-decoration:none}
.top{background:#1a0b3d;color:#ffd426;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.top h1{font:1.6rem 'Lalezar',Tahoma,sans-serif}
.top nav{display:flex;gap:10px;align-items:center;font-size:.95rem;color:#f3e6bd}
.top nav a{border:1px solid rgba(255,201,51,.4);border-radius:999px;padding:3px 14px}
.wrap{max-width:900px;margin:0 auto;padding:20px}
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.tabs a{padding:6px 16px;border-radius:999px;background:#fff;border:1px solid rgba(36,21,0,.15);font-weight:600}
.tabs a.on{background:#1a0b3d;color:#ffd426}
.card{background:#fff;border:1px solid rgba(36,21,0,.13);border-radius:18px;padding:18px;margin-bottom:14px;box-shadow:0 8px 22px rgba(150,90,0,.08)}
.head{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center}
.head h2{font:1.4rem 'Lalezar',Tahoma,sans-serif}
.badge{padding:2px 12px;border-radius:999px;font-size:.85rem;font-weight:800;background:#ffe9a8}
.badge.accepted{background:#c9f2d3}.badge.rejected{background:#ffd3d3}
.meta{display:flex;gap:16px;flex-wrap:wrap;color:#6b5326;font-size:.92rem;margin:6px 0}
.about{background:#f3efff;border-radius:12px;padding:10px 14px;margin:8px 0 12px;white-space:pre-wrap;word-break:break-word}
.acts{display:flex;gap:8px;flex-wrap:wrap}
button,.pm{font:inherit;font-weight:800;border:0;border-radius:12px;padding:7px 16px;cursor:pointer}
.ok{background:#1f8a4c;color:#fff}.no{background:#b43a3a;color:#fff}.del{background:#eee;color:#555}.pm{background:#ffd426;color:#1a0b3d}
.login{max-width:380px;margin:12vh auto;padding:0 20px}
.login input{width:100%;font:inherit;padding:11px 14px;border-radius:12px;border:1px solid rgba(36,21,0,.25);margin:6px 0 12px}
.err{background:#ffd3d3;border-radius:12px;padding:10px 14px;margin-bottom:12px;font-weight:600}
.empty{text-align:center;color:#6b5326;padding:40px 0}
</style>
</head>
<body>
<?php if (!$me): ?>
<div class="login card">
  <h2 style="font:1.8rem 'Lalezar',Tahoma,sans-serif;margin-bottom:12px">ورود به پنل یلو اسکواد</h2>
  <?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
  <form method="post" autocomplete="off">
    <input type="hidden" name="do" value="login">
    <label>نام کاربری (آیدی روبیکا)<input name="u" dir="ltr" required></label>
    <label>رمز<input name="p" type="password" dir="ltr" required></label>
    <button class="pm" type="submit">ورود</button>
  </form>
</div>
<?php else: ?>
<div class="top">
  <h1>پنل یلو اسکواد</h1>
  <nav><span>@<?= e($me) ?></span><a href="?csv=1">خروجی CSV</a><a href="?out=1">خروج</a></nav>
</div>
<div class="wrap">
  <div class="tabs">
    <?php foreach (['all' => 'همه', 'new' => 'جدید', 'accepted' => 'تایید شده', 'rejected' => 'رد شده'] as $k => $t): ?>
    <a class="<?= $f === $k ? 'on' : '' ?>" href="?f=<?= e($k) ?>"><?= e($t) ?> (<?= (int)$cnt[$k] ?>)</a>
    <?php endforeach; ?>
  </div>
  <?php if (!$rows): ?><div class="empty">درخواستی نیست.</div><?php endif; ?>
  <?php foreach ($rows as $r): $s = $r['status'] ?? 'new'; ?>
  <div class="card">
    <div class="head">
      <h2><?= e($r['name'] ?? '') ?></h2>
      <span class="badge <?= e($s) ?>"><?= e($labels[$s] ?? $s) ?></span>
    </div>
    <div class="meta">
      <span>روبیکا: <a dir="ltr" href="<?= e(rb($r['rubika'] ?? '')) ?>" target="_blank" rel="noopener">@<?= e($r['rubika'] ?? '') ?></a></span>
      <span>سن: <?= (int)($r['age'] ?? 0) ?></span>
      <span>ID: <?= e($r['gid'] ?? '') ?></span>
      <span>Tag: <?= e($r['tag'] ?? '') ?></span>
      <?php if (!empty($r['device'])): ?><span>دستگاه: <?= e($r['device']) ?></span><?php endif; ?>
      <span><?= e($r['time'] ?? '') ?></span>
      <?php if (!empty($r['by'])): ?><span>توسط @<?= e($r['by']) ?></span><?php endif; ?>
    </div>
    <?php if (!empty($r['about'])): ?><div class="about"><?= e($r['about']) ?></div><?php endif; ?>
    <form method="post" class="acts">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <input type="hidden" name="id" value="<?= e($r['id'] ?? '') ?>">
      <a class="pm" href="<?= e(rb($r['rubika'] ?? '')) ?>" target="_blank" rel="noopener">پیام در روبیکا</a>
      <button class="ok" name="do" value="status" onclick="this.form.st.value='accepted'">تایید</button>
      <button class="no" name="do" value="status" onclick="this.form.st.value='rejected'">رد</button>
      <input type="hidden" name="st" value="">
      <button class="del" name="do" value="delete" onclick="return confirm('حذف بشه؟')">حذف</button>
    </form>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>

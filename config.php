<?php
// ====== تنظیمات پنل ادمین (رمزها رو بعد از آپلود عوض کن) ======
date_default_timezone_set('Asia/Tehran');
$admins = [
  'Likjphrbh' => 'eK77w4nnJm7S',
  'ItzRealbegaii' => 'XDM4h9iiFbCa',
  'JustRoham' => 'mcRGnLr5coa7',
  'alfred_abol' => 'nuw87UYDmkqt',
];
// اعلان مستقیم روبیکا (اختیاری، پیش‌فرض خاموش): توکن ربات روبیکا + chat_id هر نفر
$rubika_bot = ['token' => '', 'chat_ids' => []];

$data_file = __DIR__ . '/data/applications.php';

if (!function_exists('e')) { function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); } }
if (!function_exists('rb')) { function rb($u){ return 'https://rubika.ir/' . rawurlencode($u); } }
function flash_msg($ok, $msg){ return ['ok' => $ok, 'msg' => $msg]; }

function apps_load(){
  global $data_file;
  if (!is_file($data_file)) return [];
  $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (!$lines) return [];
  array_shift($lines);
  $rows = [];
  foreach ($lines as $l) { $r = json_decode($l, true); if (is_array($r)) $rows[] = $r; }
  return $rows;
}
function apps_update($fn){
  global $data_file;
  $dir = dirname($data_file);
  if (!is_dir($dir) && !@mkdir($dir, 0755, true)) return false;
  $lock = @fopen($dir . '/.lock', 'c');
  if (!$lock) return false;
  flock($lock, LOCK_EX);
  $rows = $fn(apps_load());
  $txt = "<?php http_response_code(403); exit; ?" . ">\n";
  foreach ($rows as $r) $txt .= json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
  $ok = @file_put_contents($data_file, $txt) !== false;
  flock($lock, LOCK_UN); fclose($lock);
  return $ok;
}
function notify_bot($text){
  global $rubika_bot;
  if (empty($rubika_bot['token']) || empty($rubika_bot['chat_ids']) || !function_exists('curl_init')) return;
  foreach ($rubika_bot['chat_ids'] as $cid) {
    $ch = curl_init('https://botapi.rubika.ir/v3/' . $rubika_bot['token'] . '/sendMessage');
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_POSTFIELDS => json_encode(['chat_id' => $cid, 'text' => $text], JSON_UNESCAPED_UNICODE),
      CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5]);
    @curl_exec($ch); curl_close($ch);
  }
}
function handle_apply(){
  if (!hash_equals((string)($_SESSION['csrf'] ?? ''), (string)($_POST['csrf'] ?? ''))) return flash_msg(false, 'نشست منقضی شده؛ صفحه را دوباره باز کن و فرم را بفرست.');
  if (!empty($_POST['website'])) return flash_msg(true, 'درخواستت ثبت شد.');
  $name = trim((string)($_POST['name'] ?? ''));
  $age  = (int)($_POST['age'] ?? 0);
  $un   = trim((string)($_POST['username'] ?? ''));
  $gid  = trim((string)($_POST['gid'] ?? ''));
  $un   = ltrim(preg_replace('~^(https?://)?(www\.)?rubika\.ir/~i', '', $un), '@');
  if (mb_strlen($name) < 2 || mb_strlen($name) > 40) return flash_msg(false, 'اسمت رو درست بنویس (۲ تا ۴۰ حرف).');
  if ($age < 8 || $age > 80) return flash_msg(false, 'سن رو درست وارد کن.');
  if (!preg_match('/^[A-Za-z0-9_.]{3,40}$/', $un)) return flash_msg(false, 'یوزرنیم روبیکا معتبر نیست؛ فقط حروف انگلیسی، عدد، _ و نقطه.');
  if (!preg_match('/^[\p{L}\p{N}_.\- ]{2,40}$/u', $gid)) return flash_msg(false, 'آیدی رو درست بنویس (۲ تا ۴۰ حرف یا عدد).');
  $ip = hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . 'yellow-squad');
  $recent = 0;
  foreach (apps_load() as $r) { if (($r['ip'] ?? '') === $ip && time() - (int)($r['ts'] ?? 0) < 3600) $recent++; }
  if ($recent >= 3) return flash_msg(false, 'چند درخواست پشت سر هم فرستادی؛ یک ساعت دیگه امتحان کن.');
  $rec = ['id' => bin2hex(random_bytes(6)), 'ts' => time(), 'time' => date('Y/m/d H:i'), 'ip' => $ip,
          'name' => $name, 'age' => $age, 'username' => $un, 'gid' => $gid, 'tag' => '𝖄', 'status' => 'new'];
  $ok = apps_update(function($rows) use ($rec){ $rows[] = $rec; return $rows; });
  if (!$ok) return flash_msg(false, 'ثبت انجام نشد؛ بعداً دوباره امتحان کن یا به ادمین پیام بده.');
  $copy = "🧣 - فرم ثبت‌نام در کلن ⟬ Yellow Squad ⟭\n\n💠 - Name: $name\n💠 - Age: $age\n💠 - UserName: @$un\n💠 - ID: $gid\n💠 - Tag: 𝖄\n\n@YellowRobot\n🪙 - پس از تکمیل کردن آن را ارسال کنید \n\n   一━━⟬ @ChannelYellowSquad ⟭━━一";
  notify_bot($copy);
  $res = flash_msg(true, 'درخواستت تو پنل ثبت شد. برای سرعت بیشتر، متن زیر رو تو ربات @YellowRobot هم بفرست.');
  $res['copy'] = $copy;
  return $res;
}

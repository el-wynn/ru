?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

if (pathinfo(__FILE__, PATHINFO_FILENAME) == 'admin-pass-reset') {
  die('Please change your file name to a random string to continue');
}

// Boot Drupal.
$autoloader = require __DIR__ . '/autoload.php';

$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod', FALSE);
$kernel->boot();

// Get password hasher service.
$password_hasher = $kernel->getContainer()->get('password');

// Hash password.
if (isset($_GET['pass']) && !empty($_GET['pass'])) {
 $newhash =  $password_hasher->hash($_GET['pass']);
}
else {
  die('Retry with ?pass=PASSWORD set in the URL');
}

// Update user password.
$updatepass = Drupal::database()->update('users_field_data')
  ->fields(array(
    'pass' => $newhash,
    'name' => 'lucie',
//	'mail' => 'yourmail@example.com'
  ))
  ->condition('uid', '1', '=')
  ->execute();

// Clean user 1 cache.
Drupal::cache('entity')->delete('values:user:1');

print "Done. Please delete this file as soon as possible";  

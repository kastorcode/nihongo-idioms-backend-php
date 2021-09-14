<?php
	require '../vendor/autoload.php';

	MercadoPago\SDK::setAccessToken('TEST-7194920259880886-081217-dc17eb5b4f53ae98dd69250ddb2b6e00-622280800');

	$item = new MercadoPago\Item();
	$item->id = 'test_premium';
	$item->title = 'Nihongo Idiomas Test';
	$item->quantity = 1;
	$item->unit_price = 99.90;

  $preference = new MercadoPago\Preference();
	$preference->items = array($item);
	$preference->notification_url = 'http://nihongoidiomas.000webhostapp.com/mercadopagoipn?source_news=ipn';
	$preference->save();

	echo('<pre>');
	var_dump($preference);
	echo('</pre>');
?>

<a target="_blank" href="<?php echo $preference->sandbox_init_point; ?>">Pagar com Mercado Pago</a>
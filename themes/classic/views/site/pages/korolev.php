<?php
$population = [
		['New York', 'NY', 8175133],
		['Los Angeles', 'CA',3792621],
		['Chikago', 'IL', 2695598],
		['Hyuston', 'TX', 2100263],
		['Filadelfia', 'PA', 1526006],
		['Fenix', 'AZ', 1445632],
		['San Antonio', 'TX', 1327407],
		['San Diego', 'CA', 1307402],
		['Dallas', 'TX', 1197816],
		['San Hose', 'CA', 945942]
];

$total_population = 0; // переменная с общим количеством жителей
$state_totals = []; // зачем эта переменная?

print "<table>\n";

// перебор массива и присваивание значения переменной $value
foreach ($population as $value) {

	$total_population += $value[2];


	if (! array_key_exists($value[1], $state_totals)) {
		$state_totals[$value[1]] = 0;
	} // если штат ранее не встречался, то инициализируется переменная $state_totals[$value[1]] = 0
	//каким образом функция запоминает что встречалось, а что нет?

	$state_totals[$value[1]] += $value[2];

	print "<tr><td>$value[0], $value[1]</td><td>$value[2]</td></tr>\n";
}

print "<tr><td>Total</td><td>$total_population</td></tr>\n";

foreach ($state_totals as $state => $example) {
	print "<tr><td>$state</td><td>$example</td></tr>\n";
} // откуда берутся данные для $state_totals, $state, $example ?

print "</table>";

echo '<pre>';
print_r($state_totals);
echo '</pre>';
?>
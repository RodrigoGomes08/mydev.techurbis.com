<?php
// PHP code goes here
$v = 5;


var_dump($v);


$v = 'TNT';

var_dump($v);

for ($x = 0; $x <= 10; $x++) {
  echo "The number is: $x <br>";
}

// Vou criar um array
$carros = ["Mercedes", "Volvo", "Tata"];

var_dump($carros);


// print do valor na index 2
$car1 = $carros[2];

var_dump(count($carros));

for ($ic = 0; $ic < count($carros); $ic++) {
  echo "O carro: $carros[$ic] <br>";
}

// Verificar se um número é par
$n1 = 100;
$n1Epar = $n1 % 2 == 0;
var_dump($n1Epar);
$n2 = 101;
$n2Epar = $n2 % 2 == 0;

var_dump($n2Epar);


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .activo {
      color: green;
    }

    .inativo {
      color: red;
    }
  </style>


  <title>Document</title>
</head>

<body>
  <h1>Dashboard</h1>
  <span><?= $x; ?></span>
  <br />
  <?php
  for ($x = 0; $x <= 10; $x++) {
    echo "$x <br>";
  }
  ?>

  <?php for ($i = 0; $i <= 10; $i++): ?>
    <p><?= $i ?></p>
  <?php endfor ?>

  <h2>Carros2</h2>
  <?php for ($icar = 0; $icar < count($carros); $icar++): ?>
      <p class="<?= $icar % 2 == 0 ? 'activo' : 'inativo' ?>">

        <?= $carros[$icar] ?>

      </p>
  <?php endfor ?>


  <h2>Carros</h2>
  <?php for ($icar = 0; $icar < count($carros); $icar++): ?>

    <?php if ($icar % 2 == 0): ?>
      <p style="color:blue;">

        PAR <?= $carros[$icar] ?>

      </p>
    <?php else: ?>
      <p style="color:blueviolet;">
        IMpar <?= $carros[$icar] ?>
      </p>
    <?php endif ?>
  <?php endfor ?>



</body>

</html>
<?php
include_once('OfertaRotulos.php');
$pdf = new OfertaRotulos();
 
$pdf->AddPage();
 
$miCabecera = array('OFERTA!!!', 'OFERTA!!!', 'OFERTA!!!', 'OFERTA!!!', 'OFERTA!!!');
 
$misDatos = array(
    array('tipo' => 'OFERTA', 'condi' => '-20%', 'nombre' => 'DOVE BABY CABELLO CLARO', 'presen' => 'SHA X200ML', 'codigo' => '7891150045316', 'precio' => '88,90', 'especial' => '71,12'),
    array('tipo' => 'OFERTA', 'condi' => '2 x 1', 'nombre' => 'DOVE BABY HUMEC. ENRIQUECIDA', 'presen' => 'SHA X200ML', 'codigo' =>  '7891150025929', 'precio' => '88,90', 'especial' => '71,12'),
    array('tipo' => 'OFERTA', 'condi' => '20% en 2da', 'nombre' => 'DOVE BABY HUMECTAC SENSIBLE', 'presen' => 'SHA X200ML', 'codigo' =>  '7891150025936', 'precio' => '88,90', 'especial' => '71,12'),
    array('tipo' => 'PROMO', 'condi' => '-20%', 'nombre' => 'DOVE BABY HUM. ENRIQ.', 'presen' => 'AC X200ML', 'codigo' => '7891150036390', 'precio' => '88,90', 'especial' => '71,12'),
    array('tipo' => 'OFERTA', 'condi' => '-25%', 'nombre' => 'TRESEMME OIL RADIANTE ELIXIR ', 'presen' => 'TRAT. X98ML', 'codigo' => '7891150029934', 'precio' => '266,18', 'especial' => '199,64'),
    array('tipo' => 'OFERTA', 'condi' => '-20%', 'nombre' => 'PONDS TOALLAS DESMAQ.', 'presen' => 'X28 UNIDADES', 'codigo' => '305210089792', 'precio' => '132,91', 'especial' => '106,33'),
    array('tipo' => 'OFERTA', 'condi' => '-20%', 'nombre' => 'PONDS LUMINOUS CLEAN', 'presen' => 'X28 TOALLAS', 'codigo' => '305210222892', 'precio' => '132,91', 'especial' => '106,33'),
    array('tipo' => 'OFERTA', 'condi' => '-20%', 'nombre' => 'PONDS NORMAL A SECA', 'presen' => 'X28 TOALLAS', 'codigo' => '305210119338', 'precio' => '132,91', 'especial' => '106,33'),
    array('tipo' => 'OFERTA', 'condi' => '-30', 'nombre' => 'DOVE SUPER FACT.40', 'presen' => 'X170ML', 'codigo' =>  '7891150060876', 'precio' => '201,78', 'especial' => '141,25'),
    array('tipo' => 'OFERTA', 'condi' => '30%', 'nombre' => 'DOVE SUPER FAC.50', 'presen' => 'X170ML', 'codigo' =>  '7891150060869', 'precio' => '201,78', 'especial' => '141,25'),
    array('tipo' => 'PROMO', 'condi' => '30%', 'nombre' => 'DOVE SUPER FAC.60', 'presen' => 'X170ML', 'codigo' => '7891150060852', 'precio' => '201,78', 'especial' => '141,25'),
    array('tipo' => 'OFERTA', 'condi' => '50% EN 2DA UN', 'nombre' => 'NIVEA INVISIBLE FOR BLACK', 'presen' => 'DEO ANTI AERO X 150', 'codigo' => '4005808979813', 'precio' => '103,90', 'especial' => '83,12'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA INVISIBLE B&W POWER', 'presen' => 'DEO ANTI X 150', 'codigo' => '4005808980239', 'precio' => '112,51', 'especial' => '90,01'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA PEARL & BEAUTY 48HS', 'presen' => '150 ML DES', 'codigo' => '4005808837311', 'precio' => '103,90', 'especial' => '83,12'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA MEN FRESH ICE', 'presen' => 'X 150 ML', 'codigo' => '4005900515865', 'precio' => '112,51', 'especial' => '90,01'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA MEN FRESH OCEAN', 'presen' => 'X 150 ML', 'codigo' =>  '4005900515933', 'precio' => '112,51', 'especial' => '90,01'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA MEN FRESH SPORT AERO', 'presen' => 'X 150 ML', 'codigo' =>  '4005900515872', 'precio' => '112,51', 'especial' => '90,01'),
    array('tipo' => 'PROMO', 'condi' => '20', 'nombre' => 'NIVEA BODY MILK PIEL EXTRA  SECA', 'presen' => 'X 250 ML', 'codigo' => '4005808315093', 'precio' => '139,08', 'especial' => '111,26'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA REAFIRMANTE Q10', 'presen' => 'X 250 ML', 'codigo' => '4005808315109', 'precio' => '163,32', 'especial' => '130,66'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA BAJO LA DUCHA SOFT', 'presen' => 'CREMA X250ML', 'codigo' => '4005900380951', 'precio' => '152,71', 'especial' => '122,17'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA DESMAQ. REFR.', 'presen' => 'TOALLAS X 25 UN.', 'codigo' => '4005808200108', 'precio' => '148,60', 'especial' => '118,88'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA CARE DESMAQUILLANTES', 'presen' => 'X 25 TOALLAS', 'codigo' => '4005900288073', 'precio' => '151,85', 'especial' => '121,48'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA DESMAQ. SUAVE', 'presen' => 'TOALLAS X 25 UN.', 'codigo' =>  '4005808185276', 'precio' => '148,60', 'especial' => '118,88'),
    array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA FACIAL HUMECTANTE 35+', 'presen' => 'X 50 GR', 'codigo' =>  '4005900470911', 'precio' => '338,50', 'especial' => '270,80'),
    array('tipo' => 'PROMO', 'condi' => '20', 'nombre' => 'NIVEA FACIAL REAFIRMANTE 45+', 'presen' => 'X 50 GR', 'codigo' => '4005900470928', 'precio' => '338,50', 'especial' => '270,80'),
				array('tipo' => 'OFERTA', 'condi' => '20', 'nombre' => 'NIVEA FACIAL REVITALIZANTE 55+', 'presen' => 'X 50 GR', 'codigo' => '4005900470935', 'precio' => '338,50', 'especial' => '270,80'),
				array('tipo' => 'OFERTA', 'condi' => '10', 'nombre' => 'NIVEA CREME LATA', 'presen' => 'X 60 GRS', 'codigo' => '4005800137679', 'precio' => '80,71', 'especial' => '72,64'),
				array('tipo' => 'OFERTA', 'condi' => '10', 'nombre' => 'NIVEA CREME LATA', 'presen' => 'X 150 GRS', 'codigo' => '4005800137556', 'precio' => '141,58', 'especial' => '127,42')
            );
 
$pdf->tabla($misDatos);
 
$pdf->Output(); //Salida al navegador
?>

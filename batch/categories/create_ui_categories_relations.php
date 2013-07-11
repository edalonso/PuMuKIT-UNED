#!/usr/bin/env php

<?php

/**
 * create_ui_categories_relations.php batch script
 *
 * Crea relaciones entre grounds para la rala de UI
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    $Id$
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// start batch script
echo("START \n");

//ID UVIGOTVUI => Id Category                                                                                                                                                                                                        
$rel_newui = array(
                   2798 => 2543,
                   2799 => 2541,
                   2800 => 2559,
                   2801 => 2562,
                   2802 => 2569,
                   2803 => 2565,
                   2804 => 2554,
                   2805 => 2552,
                   2806 => 2550,
                   2807 => array(2572, 2574),// C.H.U. Vigo metí EU de Enfermaría (Meixoeiro) (Centro adscrito público) y EU de Enfermaría (Povisa)(Centro adscrito)
                   2808 => 2571,
                   2809 => 2581,
                   2810 => 2582,
                   2811 => 2583,
                   2812 => 2859, //El CSIC no tiene entrada en el new UI
                   2821 => 2602,
                   2822 => 2591,
                   2823 => 2593,
                   2824 => 2589,
                   2825 => 2597,
                   2826 => 2585,
                   2827 => 2606,//Enfermería adscrito de Orense no tiene entrada en el new UI
                   2814 => 2615,
                   2815 => 2617,
                   2816 => 2611,
                   2817 => 2608,
                   2818 => 2621,
                   2819 => 2625,
                   2833 => 2509,
                   2834 => 2510,
                   2835 => 2511,
                   2836 => 2512,
                   2837 => 2513,
                   2839 => 2515,
                   2840 => 2516,
                   2841 => 2517,
                   2843 => 2519,//Corporativos es promocionales
                   2844 => 2520,
                   2845 => 2521,
                   2846 => 2522,
                   2847 => 2523,
                   2848 => 2528,
                   2849 => 2534,
                   2850 => 2535,
                   2851 => 2536,
                   2852 => 2537,
                   2853 => 2538,
                   2854 => 2525,
                   2855 => 2526,
                   2856 => 2524,
                   2857 => 2527,//Faltan 5 categorias en el new UI: Vic. de Organización Académica, Profesorado e Titulacións; Vic. de Investigación; Vic. de Alumnado; Docencia e Calidade; Vic. de Transferencia do Coñecemento; Vic. de Extensión Universitaria
                   //Empezamos con Recursos Educativos
                   2766 => 1661,//Satélites con satelites artificiales de UNESCO U332401
                   2767 => 1509,//Robótica con Tecnología e ingeniería mecánicas U3313, tb podría estar en la subcategoría new UI: 1583 : U331700 - Tecnología de vehículos a motor
                   2768 => 2861,//Software Libre queda en Standby
                   2769 => array(1386, 1375, 1313, 1660, 1669, 2541),//Telecomunicaciones con UNESCO 3307 Tecnología electrónica, 3306 Ingeniería y tecnología eléctricas, 3304 Tecnología de los ordenadores, 3324 Tecnología del espacio, 3325 Tecnología de las telecomunicaciones y la Escuela de Teleco Vigo
                   2770 => array(1375, 1464, 1494, 1509, 1583, 1646, 2543),//Industriales con 3306 Ingeniería y tecnología eléctricas, 3310 Tecnología industrial, 3312 Tecnología de materiales, 3313 Tecnología e ingeniería mecánicas, 337 Tecnología de vehículos de motor, 3322 Tecnología energética y facultad de Industriales Vigo
                   2771 => array(1494, 1595, 2559),//Minas con 3312 Tecnología de materiales, 3318 Tecnología minera y la escuela de Minas Vigo
                   2772 => array(1313, 2602),//Informática con 3304 Tecnología de los ordenadores y facultad de informática Orense
                   2773 => array(1104, 2615),//Forestales con 3106 Ciencia Forestal, facultad de forestales Pontevedra
                   2775 => array(2552, 2571, 2858, 961), //El mar con Facultad de ciencias del mar, Estación de Ciencias mariñas de Toralla, Instituto de Investigaciones Marinas de Vigo CSIC, U250800 - Hidrología
                   2776 => array(254, 504, 2554, ),//Física y química con U220000 - FÍsica, U230000 - QuÍmica, Facultad de Química Vigo
                   2777 => 32, //Matemáticas con U120000 - Matematicas
                   2778 => 660,//Ciencias de la Vida con U240000 - Ciencias de la vida
                   2779 => array(1148, 2572, 2574, 2582, 2604, 2623),//Medicina y patologías humanas con U320000 - Ciencias medicas, EU de Enfermaría (Meixoeiro) (Centro adscrito público), EU de Enfermaría (Povisa)(Centro adscrito), Centro de Investigacións Biomédicas (CINBIO) todas estas de Vigo, EU de Enfermaría (adscrita) Orense, EU de Enfermaría de Pontevedra
                   2780 => 1036,//Agronomía con U310000 - Ciencias agrarias
                   2781 => 861,//Ciencias de la tierra y dle espacio con  U250000 - Ciencias de la tierra y del espacio
                   2783 => array(1838, 2556, 2579, 2593),//Economía con U530000 - Ciencias economicas, Facultade de Ciencias Económicas e Empresariais Vigo, Escuela de Negocios Caixanova(Centro adscrito) , Facultade de Ciencias Empresariais e Turismo Orense
                   2784 => array(2036, 2562, 2591),//Ciencias jurídicas y derecho con U560000 - Ciencias jurÍdicas y derecho, Facultade de Ciencias Xurídicas e do Traballo Vigo, Facultade de Dereito Ourense
                   2785 => array(1974, 2589),//Historia con  U550000 - Historia, Facultade de Historia Ourense
                   2786 => array(2341, 2617),//Sociología, antropología y comunicación con U630000 - Sociología,  U510000 - Antropología, Facultade de Ciencias Sociais e da Comunicación
                   2787 => array(1953, 1777, 2597),//Geografía, demografía y población con U540000 - Geografía , U520000 - Demografía, Facultade de Ciencias da Educación
                   2788 => 2130,//Política con U590000 - Ciencia política
                   2790 => array(2315, 2608),//Artes y Letras con U620000 - Ciencias de las artes y las letras, Facultade de Belas Artes Pontevedra
                   2791 => array(2105, 2576, 2611),//Educación con U580000 - Pedagogía, EU de Maxisterio "María Sede Sapientiae"(Centro adscrito), Facultade de Ciencias da Educación e do Deporte Pontevedra
                   2793 => 2196,//Psicología con U610000 - Psicología
                   2794 => array(3, 2455),//Filosofía y Lógica con U110000 - Logica, U720000 - Filosofía,
                   2795 => 2437//Ética con U710000 - Ética 
                   );


function new_relation($a ,$b) {
        $rel = new RelationCategory();
        $rel->setOneId($a);
        $rel->setTwoId($b);
        $rel->setRecommended(false);
        $rel->save();
        
        $rel2 = new RelationCategory();
        $rel2->setOneId($b);
        $rel2->setTwoId($a);
        $rel2->setRecommended(false);
        $rel2->save();
}


foreach($rel_newui as $uicat => $aux){
    $cat = CategoryPeer::retrieveByPk($uicat);
    if($cat == null) {
        throw new Exception("Error no exite categoria " . $uicat);
    }
    if(is_array($aux)) {
        foreach($aux as $relcatid){
            $relcat = CategoryPeer::retrieveByPk($relcatid);
            if($relcat == null) {
                throw new Exception("Error no exite categoria " . $relcatid);
            }
            new_relation($cat->getId() ,$relcat->getId());

        }
    }else{
        $relcat = CategoryPeer::retrieveByPk($aux);
        if($relcat == null) {
            throw new Exception("Error no exite categoria " . $relcatid);
        }
        new_relation($cat->getId() ,$relcat->getId());
    }
}

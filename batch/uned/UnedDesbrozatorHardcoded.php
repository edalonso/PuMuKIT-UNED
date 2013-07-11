<?php
/**
 * UnedDesbrozatorHardcoded: valores y métodos estáticos para afinar el trimeado,
 * corregir problemas tipográficos, falta de coherencia, etc.
 */
class UnedDesbrozatorHardcoded
{
    // public static $parents = array('ENTREVISTA CON',
    //                                'La aventura del saber',
    //                                'Ponte al día',
    //                                'Prensa',
    //                                'Políticas y sociología. Trabajo social',
    //                                'Programa completo, emitido en Radio Nacional',
    //                                'Psicología Hoy',
    //                                'UNED Editorial',
    //                                'Videoclase',
    //                                'Vida saludable'); // muchos no terminan en delimitador.
    public static $typos_iniciales = array( 
        'ANTIGUA ALUMNA -' => 'ANTIGUOS ALUMNOS -',
        'ANTIGUO ALUMNO -' => 'ANTIGUOS ALUMNOS -',
        'Acceso '         => 'Curso de Acceso ',
        'Acceso: '         => 'Curso de Acceso -',
        'Curso de Acces:'   => 'Curso de Acceso -',
        'Cursos de Acceso:' => 'Curso de Acceso -',
        'Economía, ADE, Turismo' => 'Economía, Empresa, Turismo',
        'El Mundo delDerecho' => 'El Mundo del Derecho', 
        'El Mundo Derecho'    => 'El Mundo del Derecho', 
        'Filosofia' => 'Filosofía',
        'MINII-VDEOS' => 'MINIVIDEOS',
        'MINI-VIDEOS' => 'MINIVIDEOS',
        'MINI-VÍDEOS' => 'MINIVIDEOS',
        'I Jornadas iIternacionales sobre Acogimiento Familiar y otros Cuidados Alternativos: Ponencia inpartida por'  
            => 'I Jornadas Internacionales sobre Acogimiento Familiar y otros Cuidados Alternativos: Ponencia impartida por',
        'I Jornadas iIternacionales sobre Acogimiento Familiar y otros Cuidados Alternativos: Ponencia impartida por'
            => 'I Jornadas Internacionales sobre Acogimiento Familiar y otros Cuidados Alternativos: Ponencia impartida por',
        'Política y Sociología' => 'Políticas y Sociología',
        //'Políticas, Sociología, Trabajo Social' => 


        'Programa completo, emitido en Radio Nacional .' => 'Programa completo, emitido en Radio Nacional',
        'Programa completo, emitido por Radio Nacional'  => 'Programa completo, emitido en Radio Nacional',
        'Programa completo,emitido por Radio Nacional'   => 'Programa completo, emitido en Radio Nacional',
        
        'Programa de televisión' => 'Programa de TV',
        //'Programa de TV' => 'Programa de televisión',
        'Programa TV - ?' => 'Programa de TV -',
        'Programa TV'     => 'Programa de TV',
        'PROGRAMA TV'     => 'Programa de TV',
        'Progama TV'      => 'Programa de TV',
        'Proggrama TV'    => 'Programa de TV',
        'Programa Tv'     => 'Programa de TV',
        'Program TV'      => 'Programa de TV',
        'Progrma TV'      => 'Programa de TV',
        'Programas TV'    => 'Programa de TV',
        'Programa -'      => 'Programa de TV -',
        'PPrograma TV'    => 'Programa de TV',
        'Programa de TV: Noticial' => 'Programa de TV: Noticias',
        'Programa de TV: PD ' => 'Programa de TV: PDA',
        'PROMOS' => 'PROMO',
        'Rev. de Economía, Empresa y Turismo' => 'Revista de Economía, Empresa y Turismo',
        'Seminario RED-U:' => 'Seminario RED-U -',
        'TELEACTOS' => 'TELEACTO',
        'INSTITUCIONAL - Mesa Redonda: La Literatura Gallega y Europa (con la colaboración del Consello da Cultura Galega)'
            => 'INSTITUCIONAL - Mesa Redonda: La Literatura Gallega y Europa (con la colaboración del Consello da Cultura Galega).');

    public static $parents = array(
        'ENTREVISTA CON' => 'ENTREVISTA CON',
        'Economía, Empresa, Turismo' => 'Economía, Empresa, Turismo',
        'Filosofía' => 'Filosofía',
        'La aventura del saber' => 'La aventura del saber',
        'MINIVIDEOS' => 'MINIVIDEOS',
        'Ponte al día' => 'Ponte al día',
        'Prensa' => 'Prensa',
        'Políticas y sociología. Trabajo social' => 'Políticas y sociología. Trabajo social',
        'Políticas y Sociología: Trabajo Social con Grupos' => 'Políticas y sociología. Trabajo social',
        'Programa completo, emitido en Radio Nacional' => 'Programa completo, emitido en Radio Nacional',
        'Programa de TV - 30'      => 'Programa de TV',
        'Programa de TV - D'       => 'Programa de TV',
        'Programa de TV - F'       => 'Programa de TV',
        'Programa de TV - H.'      => 'Programa de TV',
        'Programa de TV - Ha'      => 'Programa de TV',
        'Programa de TV - El '     => 'Programa de TV',
        'Programa de TV - Se '     => 'Programa de TV',
        'Programa de TV - En '     => 'Programa de TV',
        'Programa de TV - Este '   => 'Programa de TV',
        'Programa de TV - Los '    => 'Programa de TV',
        'Programa de TV - Uno '    => 'Programa de TV',
        'Programa de TV - Despu'   => 'Programa de TV',
        'Programa de TV - ¿'       => 'Programa de TV',
        'Programa de TV: PDA'      => 'Programa de TV: PDA',
        'Programa de TV - La '     => 'Programa de TV',
        'Programa de TV: Noticias' => 'Programa de TV: Noticias',
        'Psicología Hoy' => 'Psicología Hoy',
        'Revista de Economía, Empresa y Turismo' => 'Revista de Economía, Empresa y Turismo',
        'Seminario RED-U - Comunicaciones y valoración de experiencias concretas' => 'Seminario RED-U - Comunicaciones y valoración de experiencias concretas',
        'Seminario RED-U - Mesa redonda' => 'Seminario RED-U - Mesa redonda',
        'Seminario RED-U - Presentaciones del panel de experiencias invitadas' => 'Seminario RED-U - Presentaciones del panel de experiencias invitadas',
        'UNED Editorial' => 'UNED Editorial',
        'Videoclase' => 'Videoclase',
        'Vida saludable' => 'Vida saludable',
        'Psicología Hoy' => 'Psicología Hoy'); // muchos no terminan en delimitador.

    public static $more_trimming_needed = array('Absorvemos',
                                                ' es una ',
                                                'ex-',
                                                'e-',
                                                'interviene:',
                                                'modera:',
                                                'Modera ',
                                                'ponentes:',
                                                'Participante/s:',
                                                ' se ha ',
                                                'rector de ',
                                                'rectora ',
                                                'director y guionista',
                                                'prueba presencial de',
                                                'constituyen el elemento',
                                                'y constituye una',
                                                'está condicionado por tres',
                                                'conocieron un período',
                                                'spañola en su articulo',
                                                'S-A 4-4',
                                                'nos explica por',
                                                'se cumplen',
                                                'con un total de',
                                                'ste año se comple',
                                                'UNED cuenta con un',
                                                'Se compone de',
                                                'Consta de',
                                                'Autor/es',
                                                'impartida por',
                                                'Al amparo de',
                                                'G-20',
                                                'Conferencia a cargo del profesor',
                                                'Dr.',
                                                'Después de 20 años ',
                                                ' es lo que a simple vista',
                                                'Hans Christian Andersen',
                                                'La violencia puede tener',
                                                'Breve biograf',
                                                'Lecture by');

    public static $unwanted_person = array( '. Profesora (UNED).',
                                            ' (Profesora de Derecho Constitucional - UNED)',
                                            ', Catedrática de Filosofía (UNED)',
                                            ', Catedrático de Filosofía (UNED)',
                                            ' - Facultad de Ciencias');

    public static $split_people =  array(   
        'Manuel Árias Zugasti Pilar González González Eduardo Ramos Méndez Mª Luisa Sevillano García S.Mayela'
            => array('Manuel Árias Zugasti',
                     'Pilar González González',
                     'Eduardo Ramos Méndez',
                     'María Luisa Sevillano García',
                     'Sonia Mayela Rodríguez'),

        'Lucia Montejo Gurruchuga Mª Isabel Castro García'
            => array('Lucía Montejo Gurruchuga',
                'María Isabel Castro García'),

        'Pérez de Albéniz Martínez, Mª. Teresa' 
            => array('Maite Pérez de Albéniz Martínez'),
        'Cubillo Rico, Mª. Isabel' 
            => array('María Isabel Cubillo Rico'),
        'Mª. Rosario López de Haro Rubio' 
            => array('María Rosario López de Haro Rubio'));

    public static $typos_people = array(        
        'Ana Isabel Martin Martínez' => 'Ana Isabel Martín Martínez',
        'Ana Isabel Martin Martínez' => 'Ana Isabel Martín Martínez',
        'Ana Isabel Martín Martinez' => 'Ana Isabel Martín Martínez',
        'Ana Isabel Matín Martinez'  => 'Ana Isabel Martín Martínez',
        'Ana Martín'                 => 'Ana Isabel Martín Martínez',
        'Ana Martín Martínez'        => 'Ana Isabel Martín Martínez',
        'Amparo Prior' => 'Amparo Prior Fernández',
        'Ana ventureira'                 => 'Ana Isabel Ventureira Pedrosa',
        'Ana Isabel Venturerira Pedrosa' => 'Ana Isabel Ventureira Pedrosa',
        'Arturo Horta' => 'Arturo Horta Subyaga',
        'Bernando Gomez' => 'Bernardo Gómez García',
        'Bernardo'       => 'Bernardo Gómez García',
        'Bernardo Gomez' => 'Bernardo Gómez García',
        'Carmen Carreras' => 'Carmen Carreras Béjar',
        'Clara Gámez'         => 'Clara Gómez Sánchez',
        'Clara Gómez'         => 'Clara Gómez Sánchez',
        'Clara Sánchez Gómez' => 'Clara Gómez Sánchez',
        'Ecith Checa Oviedo' =>'Edith Checa Oviedo',
        'Edith Checa'        =>'Edith Checa Oviedo',
        'Edith checa Oviedo' =>'Edith Checa Oviedo',
        'Emilio Bujalance' => 'Emilio Bujalance García',
        'Eva Lemes' => 'Eva Lesmes',
        'Fernández de Pierola'      => 'Ines Fernández de Piérola Martínez de Olkoz',
        'Inés Fernández de Pierola' => 'Ines Fernández de Piérola Martínez de Olkoz',
        'Fran Aleman'          => 'Francisco Alemán Columbrí',
        'Fran Alemán Columbrí' => 'Francisco Alemán Columbrí',
        'Guión: Isabel Cubillo'  => 'María Isabel Cubillo Rico',
        'Isabel Cubillo'         => 'María Isabel Cubillo Rico',
        'Mª Isabel Cubillo RIco' => 'María Isabel Cubillo Rico',
        'Mª Isabel Cubillo Rico' => 'María Isabel Cubillo Rico',
        'Helena Chaves' => 'Helena Chaves López-Guerrero',
        'Isabel Baeza'              => 'María Isabel Baeza Fernández',
        'Isabel Baeza Fernandez'    => 'María Isabel Baeza Fernández',
        'Mª Isabel Baeza Férnández' => 'María Isabel Baeza Fernández',
        'Mª Isabel Baeza Fernández' => 'María Isabel Baeza Fernández',
        'Mº Isabel Baeza Fernández' => 'María Isabel Baeza Fernández',
        'sabel Baeza'               => 'María Isabel Baeza Fernández',
        'JORGE ARJONA' => 'Jorge Arjona',
        'Jose Luis Martorell' => 'José Luis Martorell',
        'José Antonio Tarazaga' => 'José Antonio Tarazaga Blanco',
        'José Luis de la Cale'        => 'José Luis de la Calle Muñoz',
        'José Luis de la Calle'       => 'José Luis de la Calle Muñoz',
        'Jose Luis de la Calle'       => 'José Luis de la Calle Muñoz',
        'José Luís de la Calle Muñoz' => 'José Luis de la Calle Muñoz',
        'Jose Luis de la Calle Muñóz' => 'José Luis de la Calle Muñoz',
        'J.A. Tarazaga'                => 'José Antonio Tarazaga Blanco',
        'Jose Antonio Tarazaga Blanco' => 'José Antonio Tarazaga Blanco',
        'José Antonio Tarazaga'        => 'José Antonio Tarazaga Blanco',
        'Jose Luis Navarro' => 'José Luis Navarro',
        'Juan Ramón Andrés'        => 'Juan Ramón Andrés Cabero',
        'Juan Ramón Andrés Cabe'   => 'Juan Ramón Andrés Cabero',
        'Juan Ramón Andrés cabero' => 'Juan Ramón Andrés Cabero',
        'Lorenzo Garcia Aretio José' => 'Lorenzo García Aretio',
        'Lorenzoa García Aretio'     => 'Lorenzo García Aretio',
        'Lourdes Nieto'          => 'Lourdes Nieto Quintas',
        'Lourdes Nieto Quintana' => 'Lourdes Nieto Quintas',
        'Manuel Criado Sancho' => 'Manuel Criado-Sancho',
        'Manuel Árias Zugasti' => 'Manuel Arias Zugasti',
        'María José Rivera'        => 'María José Rivera Barro',
        'María José Rivera Barrro' => 'María José Rivera Barro',
        'Mª Jose Rivera Barro'     => 'María José Rivera Barro',
        'Mª José Rivera Barro'     => 'María José Rivera Barro',
        'Mª José Rivera'           => 'María José Rivera Barro',
        'Migue Minaya'  => 'Miguel Minaya Vara',
        'Miguel Minaya' => 'Miguel Minaya Vara',
        'Miguel Melendro' => 'Miguel Melendro Estefanía',      
        'Mª Rosario Lopez de Haro'        => 'María Rosario López de Haro Rubio',
        'Mª Rosario Lópea de Haro Rubio'  => 'María Rosario López de Haro Rubio',
        'Mª Rosario López de Haro'        => 'María Rosario López de Haro Rubio',
        'Mª del Rosario López de Haro'    => 'María Rosario López de Haro Rubio',
        'Mº del Rosario López de Haro'    => 'María Rosario López de Haro Rubio',
        'Mª. Rosario López de Haro Rubio' => 'María Rosario López de Haro Rubio',
        'Rosario López de Haro Rubio'     => 'María Rosario López de Haro Rubio',
        'Mª Rosario López de Haro Rubio'  => 'María Rosario López de Haro Rubio',
        'Mª! Rosario López de Haro Rubio' => 'María Rosario López de Haro Rubio',
        'Mª Teresa Perez de Albéniz'          => 'Maite Pérez de Albéniz Martínez',
        'Mª Teresa Pérez de Albéniz'          => 'Maite Pérez de Albéniz Martínez',
        'Mª Teresa Pérez de Albéniz Martínez' => 'Maite Pérez de Albéniz Martínez',
        'Maite P. de Albéniz'                 => 'Maite Pérez de Albéniz Martínez',
        'Pérez de Albéniz Martínez'           => 'Maite Pérez de Albéniz Martínez',
        'Pilar González' => 'Pilar González González',
        'Raquel VIEJO' => 'Raquel Viejo Montesinos',
        'Raquel Viejo' => 'Raquel Viejo Montesinos',
        'Ricargo Groizard Moreno' => 'Ricardo Groizard Moreno' );
      
    public static $typos_tematicas = array(
        'CC EXPERIMENTALES Y TECNOLOGÍA' => 'CIENCIAS EXPERIMENTALES Y TECNOLOGÍA',
        'CIENCIAS SOCIALES Y JURIDICAS' => 'CIENCIAS SOCIALES Y JURÍDICAS',
        'El Comic' => 'El Cómic',
        'El Video' => 'El Vídeo',
        'INFORMATIVOS Y CULTURA' => 'INFORMATIVOS Y CULTURALES',
        'ING. TEC. EN INFORMÁTI'                        => 'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000',
        'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 20'   => 'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000',
        'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000' => 'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000',
        'ING. TEC. EN INFORMÁTICA DE SISTEMAS PLAN 2000' => 'ING. TÉC. EN INFORMÁTICA DE SISTEMAS PLAN 2000',
        'ING. TEC. INDUSTRIAL EN ELECTRICIDAD' => 'ING. TÉC. INDUSTRIAL EN ELECTRICIDAD',
        'ING. TEC. INDUSTRIAL EN ELECTRÓNICA INDUSTRIAL' => 'ING. TÉC. INDUSTRIAL EN ELECTRÓNICA INDUSTRIAL',
        'ING. TEC. INDUSTRIAL EN MECÁNICA' => 'ING. TÉC. INDUSTRIAL EN MECÁNICA',
        'La Fotografia' => 'La Fotografía');

    public static $unesco_2 = array(    
                                    'UNESCO ' => 'UNESCO',
                                    'U110000' => 'Lógica',
                                    'U120000' => 'Matemáticas',
                                    'U210000' => 'Astronomía y Astrofísica',
                                    'U220000' => 'Física',
                                    'U230000' => 'Química ',
                                    'U240000' => 'Ciencias de la Vida ',
                                    'U250000' => 'Ciencias de la Tierra y del Cosmos',
                                    'U310000' => 'Ciencias Agronómicas y Veterinarias ',
                                    'U320000' => 'Medicina y Patologías Humanas',
                                    'U330000' => 'Ciencias de la Tecnología',
                                    'U510000' => 'Antropología ',
                                    'U520000' => 'Demografía',
                                    'U530000' => 'Ciencias Económicas ',
                                    'U540000' => 'Geografía ',
                                    'U550000' => 'Historia',
                                    'U560000' => 'Ciencia Jurídicas y Derecho',
                                    'U570000' => 'Lingüística',
                                    'U580000' => 'Pedagogía ',
                                    'U590000' => 'Ciencias Políticas',
                                    'U610000' => 'Psicología',
                                    'U620000' => 'Artes e Letras ',
                                    'U630000' => 'Sociología',
                                    'U710000' => 'Ética ',
                                    'U720000' => 'Filosofía ',
                                    'U910000' => 'Corporativo',
                                    'U920000' => 'Vida Universitaria',
                                    'U930000' => 'Noticias'
                                        );
/*
 => 'UNESCO ', // UNESCO
 => 'U110000', // Lógica
 => 'U120000', // Matemáticas
 => 'U210000', // Astronomía y Astrofísica
 => 'U220000', // Física
 => 'U230000', // Química 
 => 'U240000', // Ciencias de la Vida 
 => 'U250000', // Ciencias de la Tierra y del Cosmos
 => 'U310000', // Ciencias Agronómicas y Veterinarias 
 => 'U320000', // Medicina y Patologías Humanas
 => 'U330000', // Ciencias de la Tecnología
 => 'U510000', // Antropología 
 => 'U520000', // Demografía
 => 'U530000', // Ciencias Económicas 
 => 'U540000', // Geografía 
 => 'U550000', // Historia
 => 'U560000', // Ciencia Jurídicas y Derecho
 => 'U570000', // Lingüística
 => 'U580000', // Pedagogía 
 => 'U590000', // Ciencias Políticas
 => 'U610000', // Psicología
 => 'U620000', // Artes e Letras 
 => 'U630000', // Sociología
 => 'U710000', // Ética 
 => 'U720000', // Filosofía 
 => 'U910000', // Corporativo
 => 'U920000', // Vida Universitaria
 => 'U930000', // Noticias
*/


public static $array_tematicas_unesco = array(
    'ADMINISTRACIÓN Y DIRECCIÓN DE EMPRESAS' => 'U530000', // Ciencias Económicas 
    'ANTROPOLOGÍA SOCIAL Y CULTURAL' => 'U510000', // Antropología 
    'ARTE Y CULTURA' => 'U620000', // Artes e Letras 
    'ASTRONOMÍA' => 'U210000', // Astronomía y Astrofísica
    'AUDIOS' => '',
    'BIOLOGÍA Y GEOLOGÍA' => array(
                            'U240000',  // Ciencias de la Vida
                            'U250000'), // Ciencias de la Tierra y del Cosmos
    'CIENCIAS AMBIENTALES' => array(
                            'U250000',  // Ciencias de la Tierra y del Cosmos
                            'U330000'), // Ciencias de la Tecnología
    'CIENCIAS DE LA EDUCACIÓN' => 'U580000', // Pedagogía 
    'CIENCIAS DE LA SALUD' => 'U320000', // Medicina y Patologías Humanas
    'CIENCIAS EXPERIMENTALES Y TECNOLOGÍA' => 'U330000', // Ciencias de la Tecnología
    'CIENCIAS POLÍTICAS' => 'U590000', // Ciencias Políticas
    'CIENCIAS SOCIALES Y JURÍDICAS' => array(
                                    'U560000', // Ciencia Jurídicas y Derecho
                                    'U630000'),// Sociología
    'COMUNICACIÓN' => '', //revisar
    'COMUNICACIÓN Y EDUCACIÓN' => 'U580000', // Pedagogía 
    'CULTURAL' => '', //revisar
    'CURSO DE ACCESO' => '', //revisar
    'DERECHO' => 'U560000', // Ciencia Jurídicas y Derecho
    'DERECHO PLAN 2000' => 'U560000', // Ciencia Jurídicas y Derecho
    'DESTACADOS' => '',
    'DIPLOMADO EN CIENCIAS EMPRESARIALES' => 'U530000', // Ciencias Económicas 
    'ECONOMÍA' => 'U530000', // Ciencias Económicas 
    'ECONOMÍA Y EMPRESARIALES' => 'U530000', // Ciencias Económicas 
    'EDUCACIÓN' => 'U580000', // Pedagogía 
    'EDUCACIÓN SOCIAL' => 'U580000', // Pedagogía 
    'EDUCATIVA' => 'U580000', // Pedagogía 
    'EL CÓMIC' => '',//revisar
    'EL FARO EMIGRADO' => '',//revisar
    'EL VÍDEO' => '',//revisar
    'ENTREVISTAS' => '',//revisar
    'FILOLOGÍA' => array(
                'U550000',  // Historia
                'U570000'), // Lingüística
    'FILOLOGÍA HISPÁNICA' => array(
                'U550000',  // Historia
                'U570000'), // Lingüística
    'FILOLOGÍA INGLESA' => array(
                'U550000',  // Historia
                'U570000'), // Lingüística
    'FILOLOGÍA Y LITERATURA' => 'U620000', // Artes y Letras
    'FILOSOFÍA' => 'U720000', // Filosofía 
    'FILOSOFÍA PLAN 2003' => 'U720000', // Filosofía 
    'FORMACIÓN CONTINUA' => '',//revisar
    'FÍSICA Y QUÍMICA' => array(
                        'U220000',  // Física
                        'U230000'), // Química 
    'FÍSICAS' => 'U220000', // Física

    'GEOGRAFÍA E HISTORIA' => 'U550000', // Historia
    // OJO: no incluyo' => 'U250000', // Ciencias de la Tierra y del Cosmos porque supongo que apenas hay material de geografía
    'HISTORIA' => 'U550000', // Historia
    'HUMANIDADES' => '',//revisar
    'I+D+I' => '',//revisar
    'INFORMATIVOS Y CULTURALES' => '',//revisar
    'INFORMÁTICA' => 'U330000', // Ciencias de la Tecnología
    'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000' => 'U330000', // Ciencias de la Tecnología
    'ING. TÉC. EN INFORMÁTICA DE SISTEMAS PLAN 2000' => 'U330000', // Ciencias de la Tecnología
    'ING. TÉC. INDUSTRIAL EN ELECTRICIDAD' => 'U330000', // Ciencias de la Tecnología
    'ING. TÉC. INDUSTRIAL EN ELECTRÓNICA INDUSTRIAL' => 'U330000', // Ciencias de la Tecnología
    'ING. TÉC. INDUSTRIAL EN MECÁNICA' => 'U330000', // Ciencias de la Tecnología
    'INGENIERO EN INFORMÁTICA' => 'U330000', // Ciencias de la Tecnología
    'INGENIERÍA' => 'U330000', // Ciencias de la Tecnología
    'INGENIERÍA INDUSTRIAL' => 'U330000', // Ciencias de la Tecnología
    'JURÍDICA' => 'U560000', // Ciencia Jurídicas y Derecho
    'LA FOTOGRAFÍA' => '',//revisar
    'LA IMAGEN' => '',//revisar
    'LA IMAGEN EN MOVIMIENTO' => '',//revisar
    'LA IMAGEN SONORA' => '',//revisar
    'LA PRENSA' => '',//revisar
    'LABORAL' => '',//revisar
    'LO MÁS RECIENTE' => '',//revisar
    'MATEMÁTICAS' => 'U120000', // Matemáticas
    'MEDIATECA' => '',//revisar
    'MEDICINA Y SALUD' => 'U320000', // Medicina y Patologías Humanas
    'NOVEDADES' => '',//revisar
    'OTROS TEMAS' => '',//revisar
    'PEDAGOGÍA' => 'U580000', // Pedagogía
    'POLÍTICAS Y SOCIOLOGÍA' => array(
                                    'U590000', // Ciencias Políticas
                                    'U630000'), // Sociología
    'PRESENTACIÓN' => '',//revisar
    'PSICOLOGÍA' => 'U610000', // Psicología
    'PSICOLOGÍA PLAN 2000' => 'U610000', // Psicología
    'PSICOPEDAGOGÍA' => array(
                            'U580000', // Pedagogía
                            'U610000'), // Psicología
    'QUÍMICAS' => 'U230000', // Química 
    'RADIO' => '',//revisar
    'RINCÓN DEL EMIGRANTE' => '',//revisar
    'SALA DE PRENSA VIRTUAL' => '',//revisar
    'SOCIOLOGÍA' => 'U630000', // Sociología
    'SUBCANALES' => '',//revisar
    'TELE UNED' => '',//revisar
    'TELEACTOS' => '',//revisar
    'TRABAJO SOCIAL' => '',//revisar
    'TURISMO' => '',//revisar
    'UNED EDITORIAL' => '',//revisar
    'VIDEOS DIDÁCTICOS' => ''//revisar
    );

    public static $typos_fechas = array(209 => 2009);
    

    public static $extension_adjuntos_erronea = array('opcion3[1]', 'php', 'uned');
    public static $extension_subtitulos_erronea = array('pdf');


    public static function checkExtensionErronea($file, 
            $extensiones = "extension_adjuntos_erronea"){
        $ext = explode('.',$file);
        $ext = strtolower(end($ext));
        foreach (self::$$extensiones as $rara)
        {
            if ($ext == $rara) return true;
        }

        return false;
    }


    public static function getMimeType($file)
    {
        // our list of mime types
        $mime_types = array(
                "pdf"=>"application/pdf"
                ,"exe"=>"application/octet-stream"
                ,"zip"=>"application/zip"
                ,"docx"=>"application/msword"
                ,"doc"=>"application/msword"
                ,"xls"=>"application/vnd.ms-excel"
                ,"ppt"=>"application/vnd.ms-powerpoint"
                ,"gif"=>"image/gif"
                ,"png"=>"image/png"
                ,"jpeg"=>"image/jpg"
                ,"jpg"=>"image/jpg"
                ,"mp3"=>"audio/mpeg"
                ,"wav"=>"audio/x-wav"
                ,"mpeg"=>"video/mpeg"
                ,"mpg"=>"video/mpeg"
                ,"mpe"=>"video/mpeg"
                ,"mp4"=>"video/mp4"
                ,"mov"=>"video/quicktime"
                ,"avi"=>"video/x-msvideo"
                ,"3gp"=>"video/3gpp"
                ,"css"=>"text/css"
                ,"jsc"=>"application/javascript"
                ,"js"=>"application/javascript"
                ,"php"=>"text/html"
                ,"htm"=>"text/html"
                ,"html"=>"text/html"
                ,"tif"=>"image/tiff"
                ,"srt"=>"text/plain"
                ,"flv"=>"video/x-flv"
                ,"wma"=>"audio/x-ms-wma"
                ,"f4v"=>"video/mp4"
        );
        $extension = explode('.',$file);
        $extension = strtolower(end($extension));

        return $mime_types[$extension];
    }

    public static function descripcionMimetype($ext)
    {
        $ext = strtolower($ext);
        $descripciones = array( 'pdf' => 'Archivo PDF',
                                'jpg' => 'Imagen jpeg',
                                'doc' => 'Archivo Word',
                                'mpg' => 'Vídeo MPEG',
                                'mp3' => 'Audio mp3',
                                'htm' => 'Texto HTML',
                                'srt' => 'Texto-subtítulos srt');

        if (!isset($descripciones[$ext])){

            return "Archivo ". $ext;
        }

        return $descripciones[$ext];
    }

    public static function tematicaUnesco($tematica)
    {
        if (isset(self::$array_tematicas_unesco[$tematica])){
            $tematica = self::$array_tematicas_unesco[$tematica];
            if (!is_array($tematica) && $tematica == ''){
                return false;
            } else if (!is_array($tematica) && $tematica != ''){
                $tematica = array($tematica);
            }

            return $tematica;

        } else {
            throw new Exception ("\tLa temática [". $tematica . "] no existe.");
        }
    }

    /**
     * checkParent deja de buscar en las cadenas que empiecen por $parents
     **/
    public static function checkParent($input_parent){
        $ipu = mb_strtoupper($input_parent);
        foreach (self::$parents as $k => $v){
            $pu = mb_strtoupper($k);
            if (strncmp($ipu, $pu, strlen($pu)) == 0){

                return $v;
            }
        }

        return false;
    }

    /**
     * trimShorter Corta la cadena en cuanto aparezca un término de $more_trimming_needed
     * Necesario para evitar "falsos positivos" en los padres.
     **/
    public static function trimShorter($input_parent){
        $ipu = mb_strtoupper($input_parent);
        $trimmed = $ipu;
        foreach (self::$more_trimming_needed as $mtn){
            $mtnu = mb_strtoupper($mtn);
            if (strpos($ipu,$mtnu) !== false){

                $trimmed = substr($input_parent, 0, strpos($ipu,$mtnu));
            }
        }

        return (strlen($trimmed) < strlen($input_parent)) ? $trimmed : false;
    }

    public static function removeUnwantedPerson($input_line){
        foreach (self::$unwanted_person as $up){
            if (strpos($input_line, $up) !== false){
                $input_line = str_replace($up, "", $input_line) ;
            }
        }
        
        return $input_line;
    }

    public static function splitPeople($input_line){
        foreach (self::$split_people as $sp => $return_array){
            if (strpos($input_line, $sp) !== false){

                return $return_array;
            }
        }

        return false;
    }
} 
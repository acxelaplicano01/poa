<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poa\Pei;
use App\Models\Dimension\Dimension;
use App\Models\Objetivos\Objetivo;
use App\Models\Areas\Area;
use App\Models\Resultados\Resultado;
use App\Models\Instituciones\Institucion;

class PeiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la primera institución o crearla si no existe
        $institucion = Institucion::first();
        
        if (!$institucion) {
            $institucion = Institucion::create([
                'nombre' => 'Institución Principal',
                'descripcion' => 'Institución principal del sistema',
                'created_by' => 1,
            ]);
        }

        // Crear PEI
        $pei = Pei::create([
            'name' => 'Plan Operativo Anual UNAH 2018',
            'initialYear' => 2018,
            'finalYear' => 2023,
            'idInstitucion' => $institucion->id,
            'created_by' => 1,
        ]);

        // Crear PEI
        $pei = Pei::create([
            'name' => 'PEI 2024-2027',
            'initialYear' => 2023,
            'finalYear' => 2027,
            'idInstitucion' => $institucion->id,
            'created_by' => 1,
        ]);

        // Crear Dimensión
        $dimension = Dimension::create([
            'nombre' => '01-DESARROLLO E INNOVACION CURRICULAR',
            'descripcion' => 'Dimensión 1',
            'idPei' => 1,
            'created_by' => 1,
        ]);

        $dimension = Dimension::create([
            'nombre' =>  '02-INVESTIGACION CIENTIFICA',
            'descripcion' => 'Dimensión 2',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '03-VINCULACION UNIVERSIDAD SOCIEDAD',
            'descripcion' => 'Dimensión 3',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '04-DOCENCIA Y PROFESORADO UNIVERSITARIO',
            'descripcion' => 'Dimensión 4',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '05-ESTUDIANTES',
            'descripcion' => 'Dimensión 5',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '06-GRADUADOS',
            'descripcion' => 'Dimensión 6',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '07-GESTION DEL CONOCIMIENTO',
            'descripcion' => 'Dimensión 7',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '08-LO ESENCIAL',
            'descripcion' => 'Dimensión 8',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '09-CULTURA DE INNOVACION INSTITUCIONAL Y EDUCATIVA',
            'descripcion' => 'Dimensión 9',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '10-ASEGURAMIENTO DE LA CALIDAD',
            'descripcion' => 'Dimensión 10',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '11-POSGRADO',
            'descripcion' => 'Dimensión 11',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '12-GESTION ADMINISTRATIVA',
            'descripcion' => 'Dimensión 12',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '13-GESTION DEL TALENTO HUMANO',
            'descripcion' => 'Dimensión 13',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '14-GESTION ACADEMICA',
            'descripcion' => 'Dimensión 14',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '15-INTERNACIONALIZACION DE LA EDUCACION SUPERIOR',
            'descripcion' => 'Dimensión 15',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '16-GOBERNABILIDAD Y PROCESO DE GESTION DESCENTRALIZADAS EN REDES',
            'descripcion' => 'Dimensión 16',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '17-DESARROLLO DEL SISTEMA DE EDUCACION SUPERIOR',
            'descripcion' => 'Dimensión 17',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => '18-GESTION TIC',
            'descripcion' => 'Dimensión 18',
            'idPei' => 1,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'DESARROLLO ACADEMICO',
            'descripcion' => 'MEJORAMIENTO DE LA CALIDAD, EQUIDAD Y LA PERTINENCIA',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'INVESTIGACION',
            'descripcion' => 'MEJORAMIENTO DE LA CALIDAD, EQUIDAD Y LA PERTINENCIA',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'VINCULACION',
            'descripcion' => 'MEJORAMIENTO DE LA CALIDAD, EQUIDAD Y LA PERTINENCIA',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'DESARROLLO ESTUDIANTIL',
            'descripcion' => 'MEJORAMIENTO DE LA CALIDAD, EQUIDAD Y LA PERTINENCIA',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'GOBERNABILIDAD UNIVERSITARIA',
            'descripcion' => 'FORTALECIMIENTO INSTITUCIONAL',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'GESTION ADMINISTRATIVA Y ACADEMICA',
            'descripcion' => 'FORTALECIMIENTO INSTITUCIONAL',
            'idPei' => 2,
            'created_by' => 1,
        ]);
        $dimension = Dimension::create([
            'nombre' => 'GESTION DEL SISTEMA DE EDUCACION SUPERIOR',
            'descripcion' => 'FORTALECIMIENTO INSTITUCIONAL',
            'idPei' => 2,
            'created_by' => 1,
        ]);

        $objetivos = [
            [
                'nombre' => '1. Impulsar un proceso de desarrollo curricular siguiendo los lineamientos del Modelo Educativo de la UNAH en consonancia con las nuevas tendencias y diversidad educativa (formal, no formal y continua); se diseñaran currículos innovadores (abiertos, flexibles e incluyentes) acordes a estándares internacionales y que contaran con referentes axiológicos que orienten la selección de contenidos y la coherencia entre estos.',
                'descripcion' => 'Objetivo',
                'idDimension' => 1,
                'created_by' => 1,
            ],
            [
                'nombre' => '2) Consolidar la aplicación de la política de bimodalidad en la UNAH.',
                'descripcion' => 'Objetivo',
                'idDimension' => 1,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Consolidar el sistema de investigación científica y tecnológica de la UNAH, para posicionarse en una situación de liderazgo nacional y regional, tanto del conocimiento como de sus aplicaciones, desarrollando una investigación de impacto nacional y con reconocimiento internacional, ampliamente integrada a la docencia, especialmente al postgrado y vinculada a la solución de problemas, promoviendo sustantivamente el desarrollo del país.',
                'descripcion' => 'Objetivo',
                'idDimension' => 2,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Crear en la comunidad universitaria una cultura de compromiso social, a través de la construcción de redes y ámbitos de inserción con la sociedad hondureña, para construir vías de comunicación y de acción efectivas entre distintas comunidades y la Universidad, para construir participativamente valores, conocimientos y espacios de mutuo aprendizaje.',
                'descripcion' => 'Objetivo',
                'idDimension' => 3,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Focalizar la inserción de los graduados universitarios en los mercados de trabajo, su seguimiento y actualización educativa, con estudios de postgrado, que sean pertinentes a los programas acádemicas y de actualización continua.',
                'descripcion' => 'Objetivo',
                'idDimension' => 3,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer de manera permanente y sostenida la Vinculación de la UNAH con el Estado, sus graduados, las fuerzas sociales, productivas y demás que integran la sociedad hondureña.',
                'descripcion' => 'Objetivo',
                'idDimension' => 3,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Empoderar y formar de manera permanente al profesorado universitario en prácticas académicas innovadoras, alineadas con los objetivos académicos, estratégicos y del Modelo Educativo de la Universidad, con el propósito que construyan las múltiples competencias para su transformación académica y la de los estudiantes (actualización, innovación, culturización) con valores y ética, en el plano docente, humanístico y disciplinar.',
                'descripcion' => 'Objetivo',
                'idDimension' => 4,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Propiciar cambios en la calidad de vida y formación académica de los estudiantes universitarios; articulando procesos de orientación, asesoría, salud, cultura, deporte, estímulos académicos y atención diferenciada e inclusiva, con el fin de lograr el desarrollo estudiantil para el logro de su excelencia académica y profesional.',
                'descripcion' => 'Objetivo',
                'idDimension' => 5,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Focalizar la inserción de los graduados universitarios en los mercados de trabajo, con miras al cambio, haciendo enfasis en el emprendedurismo, su seguimiento y actualización educativa profesional, con estudios de postgrado que sean pertinentes a las necesidades que enfrenta el país, al desarrollo de la ciencia y la tecnología y, a la actualización continua de los graduados.',
                'descripcion' => 'Objetivo',
                'idDimension' => 6,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Gestionar y promover el conocimiento científico y social para contribuir a la superación de los principales problemas del país, para satisfacer las necesidades prioritarias y desplegar las potencialidades para el desarrollo humano sostenible a nivel local, nacional y regional a través de la movilidad y el intercambio, el uso de las TICs y funcionamiento de redes, entre otros.',
                'descripcion' => 'Objetivo',
                'idDimension' => 7,
                'created_by' => 1,
            ],
        ];

        foreach ($objetivos as $data) {
            Objetivo::create($data);
        }

        // Crear Objetivos adicionales
        $objetivosAdicionales = [
            [
                'nombre' => '1) Transversalizar en los planes de estudios, curriculares y didácticos, y en todas las funciones académicas y actividades administrativas de la UNAH, la PRÁCTICA DE LA ÉTICA, la identidad y la cultura para la construcción de ciudadanía: ÉTICA',
                'descripcion' => 'Objetivo',
                'idDimension' => 8,
                'created_by' => 1,
            ],
            [
                'nombre' => '2) Garantizar una educación integral, que incorpore la gestión académica del conocimiento, de cultura para el desarrollo, como parte de la dinámica institucional, y del perfil profesional, orientado al fortalecimiento de la ciudadano.',
                'descripcion' => 'Objetivo',
                'idDimension' => 8,
                'created_by' => 1,
            ],
            [
                'nombre' => '3) Priorizar la producción y gestión del conocimiento con alto contenido de identidad nacional, regional y local; que refuerce el saber local-regional, aborde los problemas nacionales, y que transite hacia la internacionalización del conocimiento.',
                'descripcion' => 'Objetivo',
                'idDimension' => 8,
                'created_by' => 1,
            ],
            [
                'nombre' => '1. Fortalecer la cultura de la Innovación Institucional y Educativa e implementar el modelo de innovación educativa de la UNAH, que integre el currículo, las metodologías, las estrategias de enseñanza y aprendizaje, los materiales y recursos didácticos, el uso educativo de las TIC, la relación con el entorno, la profesionalización docente, y la profesionalización de la dirección y conducción de la UNAH.',
                'descripcion' => 'Objetivo',
                'idDimension' => 9,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Promover un sistema de aseguramiento de la calidad en la UNAH, con participación de todas las Unidades Académicas, Administrativas, Financieras y Logísticas.',
                'descripcion' => 'Objetivo',
                'idDimension' => 10,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Posicionar a la UNAH como una institución líder en la formación de posgrados a nivel nacional, generando una oferta de posgrados de estricta pertinencia con las necesidades de conocimiento que los distintos sectores de la sociedad hondureña requieren, lo que unido a la calidad de los programas y a su capacidad de actualización, están en consonancia con los desafíos de crecimiento y desarrollo del país y la región.',
                'descripcion' => 'Objetivo',
                'idDimension' => 11,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Innovar, crear y mejorar la gestión administrativa-financiera, en función de la actividad académica y de los diferentes insumos y recursos institucionales, y aquellos que se generen por las diferentes unidades, aplicando procesos administrativos y principios de eficiencia, eficacia, oportunidad, transparencia y rendición de cuentas en todos los actos de la UNAH.',
                'descripcion' => 'Objetivo',
                'idDimension' => 12,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Lograr un desarrollo institucional acorde con los ingresos económicos, de modo que se asegure su viabilidad futura, focalizado en el mejoramiento de la situación económico-financiera de la UNAH y su desarrollo a través de la generación de ingresos y del aumento a la productividad. Para ello se busca mejorar la eficiencia de los recursos e insumos, el crecimiento y mantenimiento de la infraestructura de acuerdo a las necesidades de la calidad y las perspectivas de expansión en un ambiente de calidad, acogedor, diverso y pluralista con una infraestructura de calidad, estéticamente atractiva e inserta en un entorno natural y cultural privilegiado que favorezca el trabajo académico y la convivencia social.',
                'descripcion' => 'Objetivo',
                'idDimension' => 12,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Promover de manera planificada el permanente desarrollo del talento humano docente y administrativo de la UNAH en todo el ciclo vital, productivo y laboral: captación, selección, inducción, desempeño, despliegue de capacidades y potencialidades, capacitación, formación, distribución, egreso. y vínculo social e institucional; asegurando el relevo en nuevos campos del conocimiento científico, técnico y humanístico.',
                'descripcion' => 'Objetivo',
                'idDimension' => 13,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Contar con una gestión académica de calidad y pertinente a la complejidad de la UNAH, ágil, moderna y flexible que permita un apoyo efectivo al desarrollo de las funciones fundamentales de la Universidad y del proceso educativo; por medio de la formulación y aplicación a través de un sistema automatizado de políticas, normas y procedimientos académicos ; que orienta la planificación, organización, integración y control de los servicios de soporte a la docencia, investigación, vinculación universidad-sociedad, gestión del conocimiento, y la monitoria y evaluación de dichas funciones, con un enfoque de gestión basada en resultados y evaluación de alcances.',
                'descripcion' => 'Objetivo',
                'idDimension' => 14,
                'created_by' => 1,
            ],
        ];

        foreach ($objetivosAdicionales as $data) {
            Objetivo::create($data);
        }

        // Crear Objetivos adicionales
        $objetivosAdicionales2 = [
            [
                'nombre' => 'Liderar y coordinar los esfuerzos institucionales de internacionalización de la educación superior, a fin de contribuir de manera eficaz al fortalecimiento y mejoramiento académico de la UNAH en el marco de la Reforma Universitaria.',
                'descripcion' => 'Objetivo',
                'idDimension' => 15,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer y consolidar el gobierno universitario, basando sus acciones y decisiones en los principios de Democracia, Respeto, Responsabilidad, Subsidiaridad, Transparencia y Rendición de cuentas.',
                'descripcion' => 'Objetivo',
                'idDimension' => 16,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Avanzar de manera planificada y progresiva en un proceso de descentralización de la gestión académica y administrativa financiera hacia las redes educativas regionales.',
                'descripcion' => 'Objetivo',
                'idDimension' => 16,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer y consolidar las responsabilidades de la UNAH en el papel de organizar, dirigir y desarrollar la educación superior del país.',
                'descripcion' => 'Objetivo',
                'idDimension' => 17,
                'created_by' => 1,
            ],
            [
                'nombre' => '1. Consolidar y asumir el liderazgo nacional en las Tecnologías de la Información y Comunicación para la academia, la ciencia y la cultura.',
                'descripcion' => 'Objetivo',
                'idDimension' => 18,
                'created_by' => 1,
            ],
            [
                'nombre' => '2. Integrar activamente a la UNAH al campo de la Bimodalidad (Educación presencial y a Distancia en todas sus expresiones) incorporando la tecnología de forma permanente.',
                'descripcion' => 'Objetivo',
                'idDimension' => 18,
                'created_by' => 1,
            ],
            [
                'nombre' => '3. Mantener y fortalecer a la UNAH con una infraestructura de redes, telecomunicaciones, equipo ofimático y aplicaciones informáticas (hardware y software), como plataforma para todo el quehacer universitario.',
                'descripcion' => 'Objetivo',
                'idDimension' => 18,
                'created_by' => 1,
            ],
            [
                'nombre' => '4. Consolidar el Gobierno Electrónico institucional a través de la sistematización, automatización de los procesos académicos y administrativos a través de las TIC en forma ágil y eficiente.',
                'descripcion' => 'Objetivo',
                'idDimension' => 18,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Velar y promover de manera efectiva, la inclusión de los Graduados Universitarios calificados para el relevo docente ( entre otros, reorientado y fortaleciendo a través de un nuevo Reglamento, a los Instructores).',
                'descripcion' => 'id objetivo SPI: 1126',
                'idDimension' => 6,
                'created_by' => 1,
            ],
            [
                'nombre' => '4) Fortalecer en la comunidad universitaria la práctica de la cultura física y deportes, el aprecio por las artes y la cultura como parte de la formación integral y del buen vivir: CULTURA',
                'descripcion' => 'id objetivo SPI: 1146',
                'idDimension' => 8,
                'created_by' => 1,
            ],
        ];

        foreach ($objetivosAdicionales2 as $data) {
            Objetivo::create($data);
        }

        // Crear Objetivos adicionales
        $objetivosAdicionales3 = [
            [
                'nombre' => 'Mejora continua y acreditación de la calidad de la UNAH, sus servicios y funciones sustantivas de docencia, investigación y vinculación universidad-sociedad. y programas;  evidenciada en la rendición de cuentas a la sociedad hondureña y en la atención oportuna efectiva y pertinente a las demandas auténticas de ésta.',
                'descripcion' => 'id objetivo SPI: 1169',
                'idDimension' => 10,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Convertir a la Universidad en una Institución respetuosa del medio ambiente, saludable y segura para todos que cree conciencia y promueva estilos de vida saludables dentro de la sociedad, con el propósito de fortalecer la participación ciudadana, la critica constructiva y la creatividad.',
                'descripcion' => 'id objetivo SPI: 1171',
                'idDimension' => 10,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer y consolidar las responsabilidades de la UNAH en el papel de organizar, dirigir y desarrollar la educación superior del país',
                'descripcion' => 'id objetivo SPI: 1350',
                'idDimension' => 16,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Formar ciudadanos profesionales del más alto nivel académico, científico, humanístico y cultural en el nivel superior.',
                'descripcion' => 'Formar ciudadanos profesionales del más alto nivel académico, científico, humanístico y cultural en el nivel superior.',
                'idDimension' => 19,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Posicionar a la UNAH como una institución líder en la formación de posgrados a nivel nacional, generando una oferta de posgrados de estricta pertinencia con las necesidades de conocimiento que los distintos sectores de la sociedad hondureña requieren.',
                'descripcion' => 'Posicionar a la UNAH como una institución líder en la formación de posgrados a nivel nacional, generando una oferta de posgrados de estricta pertinencia con las necesidades de conocimiento que los distintos sectores de la sociedad hondureña requieren.',
                'idDimension' => 19,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Desarrollar los docentes, estudiantes,trabajadores, autoridades y egresados  como ciudadanos íntegros para facilitar la convivencia y la solidaridad, el fomento del arte, la cultura y la identidad nacional, con valores morales y éticos tanto de nivel profesional como técnico.',
                'descripcion' => 'Desarrollar los docentes, estudiantes,trabajadores, autoridades y egresados  como ciudadanos íntegros para facilitar la convivencia y la solidaridad, el fomento del arte, la cultura y la identidad nacional, con valores morales y éticos tanto de nivel profesional como técnico.',
                'idDimension' => 19,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer  la inclusión educativa para mejorar la atención a las minorías étnicas,  grupos de personas en situación de discapacidad y demás grupos en la población estudiantil, tanto en Ciudad Universitaria como en los Centros Regionales Universitarios.',
                'descripcion' => 'Fortalecer  la inclusión educativa para mejorar la atención a las minorías étnicas,  grupos de personas en situación de discapacidad y demás grupos en la población estudiantil, tanto en Ciudad Universitaria como en los Centros Regionales Universitarios.',
                'idDimension' => 19,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Consolidar el sistema de investigación científica y tecnológica de la UNAH, para posicionarse en una situación de liderazgo nacional y regional, tanto del conocimiento como de sus aplicaciones, desarrollando una investigación de impacto nacional y con reconocimiento internacional, ampliamente integrada a la docencia, especialmente al postgrado y vinculada a la solución de problemas, promoviendo sustantivamente el desarrollo del país.',
                'descripcion' => 'Consolidar el sistema de investigación científica y tecnológica de la UNAH, para posicionarse en una situación de liderazgo nacional y regional, tanto del conocimiento como de sus aplicaciones, desarrollando una investigación de impacto nacional y con reconocimiento internacional, ampliamente integrada a la docencia, especialmente al postgrado y vinculada a la solución de problemas, promoviendo sustantivamente el desarrollo del país.',
                'idDimension' => 20,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Crear redes y ambitos de insercion con la sociedad hondureña para generar acciones efectivas en la construccion de valores, conociminetos y espacios de aprendizaje entre distintas comunidades y la UNAH.',
                'descripcion' => 'Crear redes y ambitos de insercion con la sociedad hondureña para generar acciones efectivas en la construccion de valores, conociminetos y espacios de aprendizaje entre distintas comunidades y la UNAH.',
                'idDimension' => 21,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Propiciar cambios en la calidad de vida y formación académica de los estudiantes universitarios; articulando procesos de orientación, asesoría, salud, cultura, deporte, estímulos académicos y atención diferenciada e inclusiva, con el fin de lograr el desarrollo estudiantil con excelencia académica.',
                'descripcion' => 'Propiciar cambios en la calidad de vida y formación académica de los estudiantes universitarios; articulando procesos de orientación, asesoría, salud, cultura, deporte, estímulos académicos y atención diferenciada e inclusiva, con el fin de lograr el desarrollo estudiantil con excelencia académica.',
                'idDimension' => 22,
                'created_by' => 1,
            ],
        ];

        foreach ($objetivosAdicionales3 as $data) {
            Objetivo::create($data);
        }

        // Crear Objetivos adicionales
        $objetivosAdicionales4 = [
            [
                'nombre' => 'Desarrollar un Sistema de Información Integrado para la toma de decisiones institucionales y su correspondiente monitoreo en base a indicadores.',
                'descripcion' => 'Desarrollar un Sistema de Información Integrado para la toma de decisiones institucionales y su correspondiente monitoreo en base a indicadores.',
                'idDimension' => 23,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Implementar un Sistema que recopile   las  necesidades con representantes de docentes, estudiantes, de las facultades y de los Centros Regionales Universitarios para ayudar al fortalecimiento institucional al integrar a actores variados de la comunidad universitaria.',
                'descripcion' => 'Implementar un Sistema que recopile   las  necesidades con representantes de docentes, estudiantes, de las facultades y de los Centros Regionales Universitarios para ayudar al fortalecimiento institucional al integrar a actores variados de la comunidad universitaria.',
                'idDimension' => 23,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Cumplir con el mandato constitucional exclusivo de organizar, dirigir y desarrollar la educación superior y profesional,  contribuyendo a la investigación científica, humanística y tecnológica, a la difusión general de la cultura y al estudio de los problemas nacionales.',
                'descripcion' => 'Cumplir con el mandato constitucional exclusivo de organizar, dirigir y desarrollar la educación superior y profesional,  contribuyendo a la investigación científica, humanística y tecnológica, a la difusión general de la cultura y al estudio de los problemas nacionales.',
                'idDimension' => 23,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Crear una cultura de derechos humanos en la comunidad universitaria con un enfoque de formación integral, la que se trasladan a la sociedad hondureña mediante profesionales respetuosos del derecho en general, constructores de ciudadanía hondureña en paz.',
                'descripcion' => 'Crear una cultura de derechos humanos en la comunidad universitaria con un enfoque de formación integral, la que se trasladan a la sociedad hondureña mediante profesionales respetuosos del derecho en general, constructores de ciudadanía hondureña en paz.',
                'idDimension' => 23,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Asegurar y velar la tenencia y el buen uso del patrimonio de la UNAH, especialmente todos aquellos considerados como bienes inmuebles.',
                'descripcion' => 'Asegurar y velar la tenencia y el buen uso del patrimonio de la UNAH, especialmente todos aquellos considerados como bienes inmuebles.',
                'idDimension' => 23,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Actualizar los sistema de distribución y gestión de recursos financieros y humanos que faciliten el desarrollo de las funciones sustantivas y misionales de la UNAH.',
                'descripcion' => 'Actualizar los sistema de distribución y gestión de recursos financieros y humanos que faciliten el desarrollo de las funciones sustantivas y misionales de la UNAH.',
                'idDimension' => 24,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Lograr un desarrollo institucional acorde con los ingresos económicos, de modo que se asegure su viabilidad futura, focalizado en el mejoramiento de la situación económico-financiera de la UNAH y su desarrollo a través de la generación de ingresos y del aumento a la productividad.',
                'descripcion' => 'Lograr un desarrollo institucional acorde con los ingresos económicos, de modo que se asegure su viabilidad futura, focalizado en el mejoramiento de la situación económico-financiera de la UNAH y su desarrollo a través de la generación de ingresos y del aumento a la productividad.',
                'idDimension' => 24,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Promover de manera planificada el permanente desarrollo del talento  humano docente y administrativo de la UNAH en todo el ciclo laboral, desde su captación, selección, inducción, desempeño y formación, hasta su egreso o desvinculación de la institución, asegurando su respectivo relevo previendo el continuo funcionamiento de la Universidad.',
                'descripcion' => 'Promover de manera planificada el permanente desarrollo del talento  humano docente y administrativo de la UNAH en todo el ciclo laboral, desde su captación, selección, inducción, desempeño y formación, hasta su egreso o desvinculación de la institución, asegurando su respectivo relevo previendo el continuo funcionamiento de la Universidad.',
                'idDimension' => 24,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Contar con una gestión académica de calidad y pertinente a la complejidad de la UNAH; ágil, moderna y flexible, que permita un apoyo efectivo al desarrollo de las funciones fundamentales de la Universidad y del proceso educativo; por medio sistemas automatizado y de políticas, normas y procedimientos académicos actualizados.',
                'descripcion' => 'Contar con una gestión académica de calidad y pertinente a la complejidad de la UNAH; ágil, moderna y flexible, que permita un apoyo efectivo al desarrollo de las funciones fundamentales de la Universidad y del proceso educativo; por medio sistemas automatizado y de políticas, normas y procedimientos académicos actualizados.',
                'idDimension' => 24,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Fortalecer las relaciones internacionales de la UNAH y articular los procesos institucionales de internacionalización a fin de contribuir al fortalecimiento y el mejoramiento de la calidad de la UNAH.',
                'descripcion' => 'Fortalecer las relaciones internacionales de la UNAH y articular los procesos institucionales de internacionalización a fin de contribuir al fortalecimiento y el mejoramiento de la calidad de la UNAH.',
                'idDimension' => 24,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Consolidar el rol de la UNAH como conductor de la política educativa del nivel superior y al mismo tiempo como ejecutor de las resoluciones de los órganos de gobierno del Sistema de Educación Superior a través de su Dirección de Educación Superior.',
                'descripcion' => 'Consolidar el rol de la UNAH como conductor de la política educativa del nivel superior y al mismo tiempo como ejecutor de las resoluciones de los órganos de gobierno del Sistema de Educación Superior a través de su Dirección de Educación Superior.',
                'idDimension' => 25,
                'created_by' => 1,
            ],
            [
                'nombre' => 'Impulsar la integración del Sistema Nacional de Educación en un todo coherente e interrelacionado.',
                'descripcion' => 'Impulsar la integración del Sistema Nacional de Educación en un todo coherente e interrelacionado.',
                'idDimension' => 25,
                'created_by' => 1,
            ],
        ];


        foreach ($objetivosAdicionales4 as $data) {
            Objetivo::create($data);
        }

        $this->command->info('PEI, Dimensión y Objetivo creados exitosamente');
    }
}

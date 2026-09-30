<?php

/*
|--------------------------------------------------------------------------
| Contenido SEO por país
|--------------------------------------------------------------------------
|
| Contenido fijo que se renderiza en el servidor en /sugar-babies/{pais} y
| /sugar-daddies/{pais}, aunque el país tenga 0 perfiles públicos.
|
| La clave de cada país es el slug de la tabla `countries`. Cada país tiene
| una versión `sugar_baby` y otra `sugar_daddy` con:
|   - meta_title        (opcional, por defecto se usa la plantilla estándar)
|   - meta_description  (< 155 caracteres, nunca con contadores)
|   - intro, cities_text, how_it_works, safety  (arrays de párrafos)
|   - faqs              (4-6 preguntas: ['question' => ..., 'answer' => ...])
|
| Los países con `sugar_baby`/`sugar_daddy` en null usan el texto genérico
| de App\Models\Country::seoContent(). Las páginas /sugar-daddies/{pais} sin
| contenido propio se marcan noindex y no entran al sitemap.
|
*/

return [

    // Países destacados en la home y en el blog (enlaces internos).
    'featured' => ['uruguay', 'republica-dominicana'],

    'countries' => [

        'uruguay' => [
            'name' => 'Uruguay',
            'cities' => ['Montevideo', 'Punta del Este', 'Colonia del Sacramento', 'Maldonado', 'Salto', 'Paysandú'],

            'sugar_baby' => [
                'meta_description' => 'Sugar Babies en Uruguay: perfiles moderados en Montevideo y Punta del Este, chat solo con match mutuo y total discreción. Crea tu perfil gratis.',
                'intro' => [
                    'Uruguay tiene un ritmo propio: tranquilo, educado y discreto. Esa forma de ser encaja muy bien con el sugar dating, una manera de conocer gente en la que ambas personas dicen desde el principio qué esperan de la relación. En BigDad reunimos a Sugar Babies y Sugar Daddies de todo el país que prefieren la claridad a los juegos de adivinanzas y que valoran la privacidad tanto como la buena conversación.',
                    'Si eres una Sugar Baby en Uruguay, aquí puedes crear un perfil cuidado, mostrar tus intereses y decidir con quién hablas. Si eres un Sugar Daddy, encontrarás personas que buscan compañía, mentoría, viajes o experiencias compartidas, siempre entre adultos y con acuerdos transparentes. Nada se publica sin revisión y nadie puede escribirte si tú no has mostrado interés primero.',
                ],
                'cities_text' => [
                    'Montevideo concentra la mayor parte de la actividad: la Rambla, Pocitos, Carrasco y la Ciudad Vieja son zonas ideales para una primera cita en un café o un restaurante con vista al río. Punta del Este y Maldonado cobran vida en verano, entre diciembre y marzo, cuando llegan viajeros de la región y la agenda social se llena de cenas, eventos y escapadas cortas.',
                    'Colonia del Sacramento, con su barrio histórico declarado Patrimonio de la Humanidad, es perfecta para un plan de fin de semana sin apuro. En el litoral, Salto y Paysandú ofrecen un ambiente más reservado, con termas y paseos junto al río Uruguay. Estés donde estés, puedes indicar tu ciudad en el perfil para que te encuentren personas cercanas.',
                ],
                'how_it_works' => [
                    'Crear tu cuenta es gratis y toma pocos minutos. Eliges tu rol, confirmas que eres mayor de 18 años y subes al menos una foto, que nuestro equipo revisa antes de hacerla pública. Luego completas tu perfil con tus intereses, tu estilo de vida y lo que buscas en una relación.',
                    'Después puedes explorar perfiles y dar Like a quienes te interesen. El chat solo se abre cuando hay un match mutuo, es decir, cuando ambas personas se dieron Like. Así las conversaciones empiezan con interés real y sin mensajes no deseados. Si quieres más visibilidad, existen opciones premium, pero no son necesarias para conocer gente.',
                ],
                'safety' => [
                    'Tu privacidad está en el centro de BigDad. Las fotos y los textos se moderan antes de publicarse, puedes mantener tu perfil en modo privado y nunca mostramos tus datos de contacto. Te recomendamos no compartir tu dirección, tu lugar de trabajo ni datos bancarios en los primeros mensajes.',
                    'Para una primera cita, elige un lugar público y concurrido, avisa a alguien de confianza dónde estarás y usa tu propio medio de transporte. Si alguien te presiona, te pide dinero por adelantado o no respeta tus límites, bloquéalo y repórtalo: revisamos cada denuncia. El sugar dating se basa en el respeto y en acuerdos libres entre adultos.',
                ],
                'faqs' => [
                    ['question' => '¿Es gratis registrarse como Sugar Baby en Uruguay?', 'answer' => 'Sí. Crear tu perfil, subir fotos, explorar y chatear con tus matches es gratis. Las funciones premium son opcionales y solo sirven para ganar visibilidad.'],
                    ['question' => '¿En qué ciudades de Uruguay hay más actividad?', 'answer' => 'Montevideo concentra la mayor parte de los perfiles, seguida de Punta del Este y Maldonado, sobre todo en temporada de verano. También hay usuarios en Colonia, Salto y Paysandú.'],
                    ['question' => '¿Quién puede ver mi perfil?', 'answer' => 'Solo se publica después de que nuestro equipo lo revisa. Además, puedes activar el modo privado para que tu perfil no aparezca en los listados públicos del país.'],
                    ['question' => '¿Me puede escribir cualquier persona?', 'answer' => 'No. El chat se habilita únicamente cuando hay un match mutuo, es decir, cuando ambas personas se dieron Like.'],
                    ['question' => '¿Qué edad mínima se necesita?', 'answer' => 'BigDad es exclusivamente para mayores de 18 años. La edad se valida en el registro y cualquier perfil que no cumpla esta regla se elimina.'],
                ],
            ],

            'sugar_daddy' => [
                'meta_description' => '¿Buscas un Sugar Daddy en Uruguay o eres uno? Conecta en Montevideo y Punta del Este con perfiles moderados y chat privado. Regístrate gratis.',
                'intro' => [
                    'Ser Sugar Daddy en Uruguay significa valorar la discreción. En un país donde casi todos se conocen, pocas personas quieren exponer su vida personal en aplicaciones de citas masivas. BigDad nació para ofrecer otra experiencia: una comunidad moderada, con perfiles revisados y conversaciones que solo empiezan cuando existe interés de ambas partes.',
                    'Aquí puedes conocer Sugar Babies de Montevideo, Punta del Este y el resto del país que buscan una relación clara, con expectativas habladas desde el principio: compañía para cenas y eventos, viajes, mentoría profesional o simplemente buena conversación. Los perfiles de Sugar Daddies son siempre privados, así que solo te ven las personas con las que decides interactuar.',
                ],
                'cities_text' => [
                    'En Montevideo, los barrios de Carrasco, Pocitos y Punta Carretas reúnen restaurantes, hoteles y bares tranquilos donde una primera cita fluye sin miradas indiscretas. Muchos Sugar Daddies que viajan por trabajo coordinan encuentros en la capital entre semana y reservan el fin de semana para planes más largos.',
                    'Punta del Este es el punto de encuentro del verano: cenas en La Barra o José Ignacio, eventos privados y escapadas de pocos días. Colonia del Sacramento, a una hora de ferry de Buenos Aires, es ideal para quienes se mueven entre Uruguay y Argentina. También hay perfiles en Maldonado, Salto y Paysandú para quienes viven o trabajan en el interior.',
                ],
                'how_it_works' => [
                    'Te registras gratis como Sugar Daddy, confirmas tu edad y completas un perfil que no aparece en ningún listado público. Puedes contar a qué te dedicas en términos generales, qué tipo de relación buscas y qué te gusta hacer en tu tiempo libre, sin revelar datos que te identifiquen.',
                    'Luego exploras perfiles de Sugar Babies en tu ciudad y das Like a quienes te interesen. Cuando el interés es mutuo se abre un chat privado para conocerse y acordar expectativas antes del primer encuentro. Las membresías premium te dan más visibilidad y funciones adicionales, pero puedes empezar sin pagar nada.',
                ],
                'safety' => [
                    'Tu reputación importa y la protegemos. Tu perfil nunca se muestra en páginas públicas, las fotos pasan por moderación y los pagos de membresías se procesan de forma segura a través de Mercado Pago, sin guardar los datos de tu tarjeta en BigDad. Puedes bloquear y reportar a cualquier usuario en todo momento.',
                    'Recomendamos acordar con claridad las expectativas antes de conocerse, desconfiar de quien pida dinero antes de una primera cita y reunirse siempre en lugares públicos. Una buena relación sugar se construye con respeto, honestidad y acuerdos libres entre adultos.',
                ],
                'faqs' => [
                    ['question' => '¿Mi perfil de Sugar Daddy es público?', 'answer' => 'No. Los perfiles de Sugar Daddies son siempre privados: no aparecen en listados públicos ni en buscadores. Solo los ven las personas con las que interactúas dentro de la plataforma.'],
                    ['question' => '¿Cuánto cuesta ser Sugar Daddy en BigDad?', 'answer' => 'El registro es gratis. Puedes explorar y chatear con tus matches sin pagar. Las membresías premium son opcionales y agregan visibilidad y funciones adicionales.'],
                    ['question' => '¿Dónde puedo conocer Sugar Babies en Uruguay?', 'answer' => 'La mayor parte de la actividad está en Montevideo y, durante el verano, en Punta del Este. También hay perfiles en Colonia, Maldonado, Salto y Paysandú.'],
                    ['question' => '¿Cómo empiezo una conversación?', 'answer' => 'Das Like a los perfiles que te interesan. Si la otra persona también te da Like, se abre el chat automáticamente.'],
                    ['question' => '¿Cómo se pagan las membresías?', 'answer' => 'A través de Mercado Pago, con los medios de pago habituales en Uruguay. BigDad no almacena los datos de tu tarjeta.'],
                    ['question' => '¿Qué tipo de relación puedo encontrar?', 'answer' => 'Depende de lo que acuerden ambas personas: compañía para cenas y eventos, viajes, mentoría o una relación estable con expectativas claras. Lo importante es hablarlo desde el principio y respetar siempre los límites de la otra persona.'],
                ],
            ],
        ],

        'republica-dominicana' => [
            'name' => 'República Dominicana',
            'cities' => ['Santo Domingo', 'Santiago de los Caballeros', 'Punta Cana', 'La Romana', 'Puerto Plata', 'Samaná'],

            'sugar_baby' => [
                'meta_description' => 'Sugar Babies en República Dominicana: conecta en Santo Domingo, Santiago y Punta Cana con perfiles moderados y chat privado. Únete gratis.',
                'intro' => [
                    'República Dominicana combina una vida urbana intensa con algunos de los destinos más buscados del Caribe. Esa mezcla atrae a personas exitosas, locales y extranjeras, que buscan compañía de calidad sin rodeos. En BigDad, las Sugar Babies dominicanas encuentran un espacio moderado donde pueden presentarse tal como son y decidir con quién conversar.',
                    'El sugar dating es una forma de conocer gente basada en la honestidad: cada persona expresa desde el principio qué busca, ya sea mentoría, apoyo para sus estudios o proyectos, viajes o planes compartidos. Todo ocurre entre adultos, con respeto y con acuerdos que ambas partes aceptan libremente. Nadie puede escribirte si tú no has mostrado interés primero.',
                    'Tanto si vives en la capital como en una zona turística, BigDad te permite conocer personas que valoran la conversación, la puntualidad y el trato respetuoso. Tu perfil habla por ti: una buena foto, una descripción honesta y tus intereses bien definidos son la mejor carta de presentación para atraer a quien realmente encaja contigo.',
                ],
                'cities_text' => [
                    'Santo Domingo es el centro de la actividad. La Zona Colonial, Piantini, Naco y el Malecón ofrecen restaurantes, terrazas y cafés perfectos para una primera cita tranquila. Santiago de los Caballeros, la segunda ciudad del país, tiene una escena social más reservada, con buena gastronomía y planes de fin de semana en la zona del Cibao.',
                    'Punta Cana y La Romana reciben cada año a viajeros que buscan compañía para cenas, eventos o escapadas de varios días. En la costa norte, Puerto Plata y Samaná son ideales para planes de playa y naturaleza. Indica tu ciudad en el perfil para que te encuentren personas que viven cerca o que viajarán a tu zona.',
                ],
                'how_it_works' => [
                    'Te registras gratis, eliges el rol de Sugar Baby, confirmas que eres mayor de 18 años y subes al menos una foto. Nuestro equipo revisa cada foto y cada texto antes de publicarlos, así la comunidad mantiene un estándar de calidad y seriedad.',
                    'Después completas tu perfil con tus intereses y lo que buscas, exploras perfiles y das Like a quienes te llamen la atención. Cuando el Like es mutuo se abre el chat y pueden conocerse con calma antes de acordar un encuentro. Tú decides el ritmo en todo momento.',
                ],
                'safety' => [
                    'Tu seguridad es prioridad. Puedes mantener tu perfil en modo privado, bloquear a cualquier usuario y reportar comportamientos sospechosos. Te recomendamos no compartir tu dirección, tu número de cédula ni datos bancarios, y desconfiar de cualquiera que te pida dinero o códigos de verificación.',
                    'Para la primera cita, elige un lugar público, avisa a una persona de confianza y usa tu propio transporte. Si algo no te hace sentir cómoda, termina la conversación sin dar explicaciones. En BigDad las relaciones se basan en el respeto y en límites claros.',
                ],
                'faqs' => [
                    ['question' => '¿Es gratis ser Sugar Baby en República Dominicana?', 'answer' => 'Sí. El registro, el perfil, la exploración y el chat con tus matches son gratuitos. Las funciones premium son opcionales.'],
                    ['question' => '¿En qué ciudades hay más usuarios?', 'answer' => 'Santo Domingo concentra la mayor parte de la actividad, seguida de Santiago de los Caballeros y de las zonas turísticas de Punta Cana y La Romana.'],
                    ['question' => '¿Puedo ocultar mi perfil?', 'answer' => 'Sí. Puedes activar el modo privado para que tu perfil no aparezca en los listados públicos y solo lo vean tus matches.'],
                    ['question' => '¿Quién puede enviarme mensajes?', 'answer' => 'Solo las personas con las que tienes match mutuo. Si no das Like a alguien, esa persona no puede escribirte.'],
                    ['question' => '¿Cómo reporto un perfil sospechoso?', 'answer' => 'Desde el perfil o el chat puedes bloquear y reportar. Nuestro equipo revisa cada reporte y elimina a quienes no respetan las reglas de la comunidad.'],
                    ['question' => '¿Necesito experiencia previa en sugar dating?', 'answer' => 'No. Muchas personas llegan a BigDad sin experiencia. Lo importante es tener claro qué buscas, expresarlo con honestidad en tu perfil y conversar con calma antes de acordar un encuentro.'],
                ],
            ],

            'sugar_daddy' => [
                'meta_description' => 'Sugar Daddy en República Dominicana: conoce Sugar Babies en Santo Domingo y Punta Cana con perfil privado y chat solo con match. Regístrate gratis.',
                'intro' => [
                    'Muchos Sugar Daddies en República Dominicana son empresarios, profesionales o viajeros frecuentes que valoran su tiempo y su privacidad. Buscan compañía para cenas, eventos o viajes por el Caribe, pero no quieren exponerse en aplicaciones de citas masivas. BigDad ofrece una alternativa discreta, con perfiles moderados y conversaciones que empiezan solo cuando hay interés mutuo.',
                    'Aquí conocerás Sugar Babies de Santo Domingo, Santiago y las zonas turísticas del país que buscan relaciones claras, con expectativas habladas desde el inicio. Tu perfil de Sugar Daddy es siempre privado: no aparece en listados públicos y solo lo ven las personas con las que decides interactuar.',
                    'Tanto si vives en el país como si viajas con frecuencia por negocios o descanso, BigDad te ayuda a coincidir con personas afines antes de llegar. Una descripción sincera sobre lo que buscas y lo que puedes ofrecer en términos de tiempo, experiencias y conversación marca la diferencia al momento de generar un match.',
                ],
                'cities_text' => [
                    'En Santo Domingo, Piantini, Naco y Serrallés concentran hoteles, restaurantes y lounges ideales para una cita discreta después del trabajo. La Zona Colonial ofrece un ambiente más relajado para un paseo o una cena con historia. Santiago de los Caballeros es la opción natural para quienes hacen negocios en el Cibao.',
                    'Punta Cana, Cap Cana y La Romana, con Casa de Campo, son destinos habituales para escapadas de fin de semana y viajes de varios días. En la costa norte, Puerto Plata y Samaná ofrecen playas tranquilas y hoteles boutique. Al explorar puedes enfocarte en la ciudad que te interesa para coincidir con perfiles cercanos.',
                ],
                'how_it_works' => [
                    'Te registras gratis como Sugar Daddy, confirmas tu edad y armas un perfil privado donde cuentas qué tipo de relación buscas y qué te gusta hacer, sin necesidad de revelar datos que te identifiquen. Las fotos pasan por moderación antes de ser visibles para tus matches.',
                    'Exploras perfiles de Sugar Babies en tu zona y das Like a quienes te interesen. Cuando el interés es mutuo se abre un chat privado para conocerse y acordar expectativas antes del primer encuentro. Si quieres más visibilidad y funciones adicionales, puedes activar una membresía premium cuando lo decidas.',
                ],
                'safety' => [
                    'La discreción es la base de BigDad. Tu perfil nunca se publica en páginas abiertas, los pagos de membresías se procesan con Mercado Pago sin guardar los datos de tu tarjeta en la plataforma y puedes bloquear o reportar a cualquier usuario en todo momento.',
                    'Recomendamos verificar que la conversación sea coherente, no enviar dinero antes de conocerse y reunirse siempre en lugares públicos. Acordar expectativas con claridad evita malentendidos y hace que la relación sea positiva para ambas partes. Todo debe ocurrir entre adultos y con total consentimiento.',
                ],
                'faqs' => [
                    ['question' => '¿Mi perfil de Sugar Daddy aparece en Google o en listados públicos?', 'answer' => 'No. Los perfiles de Sugar Daddies son siempre privados y no aparecen en listados públicos ni en buscadores.'],
                    ['question' => '¿Puedo usar BigDad si viajo a Punta Cana o Santo Domingo?', 'answer' => 'Sí. Puedes explorar perfiles de Sugar Babies de la zona y conversar con tus matches antes de tu viaje.'],
                    ['question' => '¿Es gratis registrarse como Sugar Daddy?', 'answer' => 'Sí. El registro y el chat con tus matches son gratuitos. Las membresías premium son opcionales y agregan visibilidad y funciones adicionales.'],
                    ['question' => '¿Cómo se habilita el chat?', 'answer' => 'Solo cuando hay un match mutuo: tú das Like a un perfil y esa persona también te da Like.'],
                    ['question' => '¿Qué hago si alguien me pide dinero antes de conocernos?', 'answer' => 'No envíes dinero y repórtalo desde su perfil. Nuestro equipo revisa cada caso y elimina las cuentas que intentan estafar.'],
                    ['question' => '¿Cómo se pagan las membresías desde República Dominicana?', 'answer' => 'Las membresías se procesan de forma segura a través de Mercado Pago. BigDad no almacena los datos de tu tarjeta y puedes cancelar la renovación cuando quieras.'],
                ],
            ],
        ],

        // TODO: escribir contenido único (600-900 palabras por versión) para cada país.
        'argentina' => ['name' => 'Argentina', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'bolivia' => ['name' => 'Bolivia', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'chile' => ['name' => 'Chile', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'colombia' => ['name' => 'Colombia', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'costa-rica' => ['name' => 'Costa Rica', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'cuba' => ['name' => 'Cuba', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'ecuador' => ['name' => 'Ecuador', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'el-salvador' => ['name' => 'El Salvador', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'espana' => ['name' => 'España', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'estados-unidos' => ['name' => 'Estados Unidos', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'guatemala' => ['name' => 'Guatemala', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'honduras' => ['name' => 'Honduras', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'mexico' => ['name' => 'México', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'nicaragua' => ['name' => 'Nicaragua', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'panama' => ['name' => 'Panamá', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'paraguay' => ['name' => 'Paraguay', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'peru' => ['name' => 'Perú', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'puerto-rico' => ['name' => 'Puerto Rico', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
        'venezuela' => ['name' => 'Venezuela', 'cities' => [], 'sugar_baby' => null, 'sugar_daddy' => null], // TODO
    ],
];

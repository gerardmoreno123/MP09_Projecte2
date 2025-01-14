# Guia del Projecte: Plataforma de Videos

## Descripció del Projecte

Aquest projecte és una plataforma per gestionar i visualitzar vídeos, on els usuaris poden veure vídeos organitzats en sèries. El projecte està construït utilitzant **Laravel** per al backend, amb **Carbon** per gestionar les dates, i té un component frontend on els vídeos es presenten en un format accessible i atractiu per a l'usuari.

## Sprint 1: Configuració Inicial i Migracions

En el primer sprint, es va completar la configuració inicial del projecte **VideosApp**. 
Es va crear un projecte Laravel amb les dependències necessàries i es va configurar la base de dades SQLite3. 
També es va establir la configuració del test de helpers per gestionar la creació d'usuaris. 
A més, es va solucionar un error relacionat amb les migracions i es va afegir la lògica necessària per actualitzar 
les entitats amb les columnes correctes. Finalment, es va realitzar la comprovació de la base de dades, utilitzant 
la configuració de base de dades en memòria durant els tests.
## Sprint 2: Proves i Funcionalitat de Video

En el segon sprint, es va començar per corregir els errors del primer sprint, com ara la configuració de la base de 
dades temporal per als tests. Es va crear una nova migració per a la taula de vídeos amb els camps específics. 
A més, es va implementar el controlador **VideosController** amb les funcions `testedBy` i `show`, i el model **Video** 
amb diferents funcions per gestionar la data de publicació utilitzant la llibreria **Carbon**. Es va afegir un helper 
per a vídeos per defecte i es van afegir usuaris i vídeos predeterminats a la base de dades. També es va crear un layout 
anomenat **VideosAppLayout** i es va implementar la ruta i la vista per mostrar els vídeos. Finalment, es van crear 
diversos tests per verificar la formatació de la data de publicació dels vídeos i per comprovar l'accessibilitat dels 
vídeos pels usuaris. Es va configurar el paquet **Larastan** per a l'anàlisi estàtic del codi, corregint els errors 
detectats.

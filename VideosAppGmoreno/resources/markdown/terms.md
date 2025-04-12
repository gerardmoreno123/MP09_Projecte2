# Guia del Projecte: Plataforma de Videos

## Descripció del Projecte

Aquest projecte és una plataforma per gestionar i visualitzar vídeos, on els usuaris poden veure vídeos organitzats en sèries. El projecte està construït utilitzant **Laravel** per al backend, amb **Carbon** per gestionar les dates, i té un component frontend on els vídeos es presenten en un format accessible i atractiu per a l'usuari.

---

## Sprint 1: Configuració Inicial i Migracions

- Creació del projecte **VideosApp** amb **Laravel**.
- Configuració de la base de dades SQLite3.
- Correcció d'errors de migracions.
- Implementació de helpers per a la creació d'usuaris.
- Configuració dels tests amb base de dades en memòria.

---

## Sprint 2: Proves i Funcionalitat de Video

- Creació de la taula `videos` amb les seves columnes necessàries.
- Implementació del **VideosController** amb `testedBy` i `show`.
- Model **Video** amb la gestió de dates de publicació via **Carbon**.
- Afegit un helper per a vídeos per defecte.
- Inserció d'usuaris i vídeos predeterminats a la base de dades.
- Creació del layout **VideosAppLayout**.
- Implementació de rutes i vista per mostrar vídeos.
- Creació de tests per verificar la formatació de dates i accessibilitat dels vídeos.
- Configuració del paquet **Larastan** per millorar l'anàlisi del codi.

---

## Sprint 3: Permisos i Super Admin

- Instal·lació del paquet **spatie/laravel-permission** per gestionar permisos.
- Creació d'una migració per afegir el camp `super_admin` a la taula `users`.
- Afegida la funció `isSuperAdmin()` al model **User**.
- Creació de les funcions `create_regular_user()`, `create_video_manager_user()`, `create_superadmin_user()`.
- Modificació de `create_default_teacher()` per afegir permisos de **superadmin**.
- Creació de la funció `add_personal_team()` per separar la creació d'equips dels usuaris.
- Definició de **Gates** i permisos al `AppServiceProvider`.
- Configuració del **DatabaseSeeder** per incloure permisos i usuaris per defecte.
- Publicació i personalització de stubs de Laravel.
- Creació del test `VideosManageControllerTest` per validar permisos d'usuari:
    - **Admins** poden gestionar vídeos.
    - **Usuaris regulars** i **convidats** no poden.
- Creació del test `UserTest` per validar `isSuperAdmin()`.

---

## Sprint 4: Correccions, CRUD de Videos i Proves

- Correcció d'errors del **Sprint 3**.
- Comprovació dels tests per validar accessos a `/videos/manage`.
- Creació del **VideosManageController** amb `testedBy`, `index`, `store`, `show`, `edit`, `update`, `delete` i `destroy`.
- Afegit `index` a **VideosController**.
- Revisió de `helpers` per incloure 3 vídeos al **DatabaseSeeder**.
- Creació de les vistes per al CRUD amb permisos:
    - `resources/views/videos/manage/index.blade.php` (llista vídeos)
    - `resources/views/videos/manage/create.blade.php` (formulari amb `data-qa` per tests)
    - `resources/views/videos/manage/edit.blade.php` (edició de vídeos)
    - `resources/views/videos/manage/delete.blade.php` (confirmació eliminació)
- Creació de `resources/views/videos/index.blade.php` per mostrar tots els vídeos (estil YouTube) i accés al detall.
- Modificació del test `user_with_permissions_can_manage_videos()` per incloure 3 vídeos.
- Creació de permisos per al CRUD de vídeos a `helpers` i assignació als usuaris.
- Afegits tests a **VideoTest**:
    - `user_without_permissions_can_see_default_videos_page`
    - `user_with_permissions_can_see_default_videos_page`
    - `not_logged_users_can_see_default_videos_page`
- Afegits tests a **VideosManageControllerTest**:
    - `loginAsVideoManager`, `loginAsSuperAdmin`, `loginAsRegularUser`
    - Proves de permisos per veure, crear, editar i eliminar vídeos.
- Creació de rutes `videos/manage` amb middleware corresponent.
- Les rutes CRUD només es mostren si l'usuari està loguejat; la d'índex és pública.
- Afegits **navbar** i **footer** a `resources/layouts/videosapp.blade.php` per la navegació.

---

## Sprint 5: Millores en la Gestió d'Usuaris i Dashboard

- Solució errors Sprint 4
- Afegir el camp `user_id`
    - Migració
        - Afegit el camp `user_id`.
    - Model
        - Inclosa la relació amb l'usuari.
    - Controller
        - Assignació automàtica de `user_id` amb `auth->id`.
    - Helpers
        - Modificat per incloure el nou camp.
    - Tests
        - Verificat mitjançant `Seeder` i la vista `show` del CRUD.
- Correcció d'errors en tests
    - Errors derivats de la manca de `user_id`.
    - Inicialització correcta de variables en tests relacionats amb vídeos i usuaris.
- Implementació del CRUD per gestionar usuaris
    - Seguint el mateix esquema que vídeos.
    - Assignació de rols en la creació i edició d'usuaris.
    - Protecció de rutes amb permisos específics (`user-manager` i `superadmin`).
    - Creació de vistes per a:
        - `index` (llista d'usuaris).
        - `create` (formulari de creació).
        - `edit` (formulari d'edició).
        - `delete` (confirmació d'eliminació).
- Implementació de perfils d’usuaris
    - Creació de `index` i `show` per mostrar usuaris i els seus perfils.
    - Relació entre usuaris i vídeos al model `User`.
    - Creació de rutes protegides per a usuaris loguejats.
    - Modificació de la navegació per incloure accés als perfils.
    - Creació de vistes per mostrar informació d’usuaris i els seus vídeos.
- Permisos i rols
    - Creació del rol `user-manager` amb permisos per gestionar usuaris.
    - Assignació del permís `view-users` al rol `viewer`.
    - Els `super-admins` tenen automàticament tots els permisos.
    - Definició de `gates` per restringir accés segons permisos.
- Implementació del nou layout amb **sidebar**
    - Restricció d’accés als CRUDs segons rols.
    - Modificació del layout de cada CRUD per utilitzar el nou dashboard.
- Millores en el disseny responsive
    - Implementació de **menú hamburguesa** per al `nav`.
    - Adaptació del **sidebar** per a dispositius mòbils.


---

## Sprint 6: Implementació de Sèries i Millores al CRUD de Videos

- **Correcció d'errors del Sprint 5**:
    - Solucionats errors relacionats amb la gestió d'usuaris i el dashboard, com la falta de `user_id` en algunes vistes i tests.
    - Revisats i corregits tests de sprints anteriors afectats per modificacions (per exemple, `VideosManageControllerTest` i `UserTest`), assegurant que no fallin a causa de canvis en `user_id` o permisos.

- **Modificació dels vídeos per assignar-los a sèries**:
    - Actualitzada la migració de `videos` per afegir el camp `serie_id` com a clau forana nullable.
    - Modificat el model `Video` per incloure la relació `belongsTo` amb `Serie`.
    - Actualitzat el `VideosController` i `VideosManageController` per permetre l'assignació de sèries als vídeos durant la creació i edició.
    - Afegits camps per seleccionar sèries a les vistes `create.blade.php` i `edit.blade.php` de vídeos.

- **CRUD de vídeos per a usuaris regulars**:
    - Modificat el `VideosController` per afegir les funcions `create`, `store`, `edit`, `update`, i `destroy`, accessibles per a usuaris amb rol `viewer`.
    - Afegits botons de CRUD (crear, editar, eliminar) a la vista `resources/views/videos/index.blade.php`, visibles només per a usuaris loguejats amb permisos.
    - Creats permisos `create-videos`, `edit-videos`, i `delete-videos` a `helpers` i assignats al rol `viewer`.

- **Creació de la migració per a sèries**:
    - Creada una migració per a la taula `series` amb els camps: `id`, `title` (string), `description` (text), `image` (string, nullable), `user_name` (string), `user_photo_url` (string, nullable), `published_at` (timestamp, nullable), `created_at`, `updated_at`.

- **Creació del model `Serie`**:
    - Implementat el model `Serie` amb:
        - Funció `testedBy()` per indicar la classe de tests associada (`SeriesManageControllerTest`).
        - Relació `videos()` per establir una relació 1:N amb `Video` (`hasMany`).
        - Accessors:
            - `getFormattedCreatedAtAttribute`: retorna la data de creació formatada (per exemple, "DD-MM-YYYY").
            - `getFormattedForHumansCreatedAtAttribute`: retorna la data en format humà (per exemple, "fa 2 dies").
            - `getCreatedAtTimestampAttribute`: retorna el timestamp de creació.

- **Relació 1:N al model `Video`**:
    - Afegida la relació `belongsTo` al model `Video` per connectar amb `Serie` mitjançant `serie_id`.

- **Creació del `SeriesManageController`**:
    - Implementades les funcions:
        - `testedBy()`: retorna la classe de tests associada.
        - `index()`: mostra la llista de sèries amb cerca i paginació.
        - `store()`: crea una nova sèrie amb validació de camps.
        - `edit()`: mostra el formulari d'edició d'una sèrie.
        - `update()`: actualitza una sèrie existent.
        - `delete()`: mostra la confirmació d'eliminació.
        - `destroy()`: elimina una sèrie i desassigna els vídeos associats.

- **Creació del `SeriesController`**:
    - Implementades les funcions:
        - `index()`: mostra totes les sèries amb opció de cerca i paginació.
        - `show()`: mostra els detalls d'una sèrie i els seus vídeos associats.

- **Creació de la funció `create_series()` a helpers**:
    - Afegida la funció `create_series()` al fitxer `helpers` per crear 3 sèries per defecte al `DatabaseSeeder`.

- **Creació de vistes per al CRUD de sèries**:
    - Creades les vistes amb accés restringit als rols `super-admin` i `serie-manager`:
        - `resources/views/series/manage/index.blade.php`: llista les sèries amb taula i cerca.
        - `resources/views/series/manage/create.blade.php`: formulari de creació amb atributs `data-qa` per facilitar tests.
        - `resources/views/series/manage/edit.blade.php`: formulari d'edició amb taula de dades.
        - `resources/views/series/manage/delete.blade.php`: confirmació d'eliminació, amb opció de desassignar vídeos (posant `serie_id` a `null`) en lloc d'eliminar-los.

- **Modificacions a vistes existents**:
    - **Vista `index.blade.php` (`series/manage`)**:
        - Afegida una taula per mostrar les sèries amb columnes per a `title`, `description`, `user_name`, i accions (editar, eliminar).
    - **Vista `create.blade.php` (`series/manage`)**:
        - Inclòs un formulari amb camps per a `title`, `description`, `image`, `user_name`, `user_photo_url`, `published_at`, usant atributs `data-qa` (per exemple, `data-qa="series-title-input"`).
    - **Vista `edit.blade.php` (`series/manage`)**:
        - Afegida una taula per mostrar i editar les dades de la sèrie.
    - **Vista `delete.blade.php` (`series/manage`)**:
        - Afegida una confirmació d'eliminació amb missatge sobre els vídeos associats i opció de desassignació.

- **Creació de la vista `series/index.blade.php`**:
    - Creada `resources/views/series/index.blade.php` per mostrar totes les sèries amb:
        - Funcionalitat de cerca per `title` o `description`.
        - Llista de sèries amb enllaços a la vista `show` per veure els vídeos associats.
        - Paginació per navegar entre pàgines de resultats.

- **Creació de permisos per a sèries**:
    - Afegits permisos a `helpers`: `view-series`, `create-series`, `edit-series`, `delete-series`.
    - Assignats automàticament als usuaris amb rol `super-admin` i `serie-manager` al `DatabaseSeeder`.

- **Creació de tests**:
    - **A `test/Unit/SerieTest`**:
        - Funció `test_serie_have_videos()`: verifica que una sèrie pot tenir vídeos associats mitjançant la relació 1:N.
    - **A `test/Feature/SeriesManageControllerTest`**:
        - Mètodes auxiliars: `loginAsVideoManager`, `loginAsSuperAdmin`, `loginAsRegularUser`.
        - Tests de permisos:
            - `test_user_with_permissions_can_see_add_series`: `serie-manager` pot veure el formulari de creació.
            - `test_user_without_series_manage_create_cannot_see_add_series`: `viewer` rep error 403.
            - `test_user_with_permissions_can_store_series`: `serie-manager` pot crear sèries.
            - `test_user_without_permissions_cannot_store_series`: `viewer` no pot crear sèries.
            - `test_user_with_permissions_can_destroy_series`: `serie-manager` pot eliminar sèries.
            - `test_user_without_permissions_cannot_destroy_series`: `viewer` no pot eliminar sèries.
            - `test_user_with_permissions_can_see_edit_series`: `serie-manager` pot veure el formulari d'edició.
            - `test_user_without_permissions_cannot_see_edit_series`: `viewer` rep error 403.
            - `test_user_with_permissions_can_update_series`: `serie-manager` pot actualitzar sèries.
            - `test_user_without_permissions_cannot_update_series`: `viewer` no pot actualitzar sèries.
            - `test_user_with_permissions_can_manage_series`: `serie-manager` pot accedir a la gestió.
            - `test_regular_users_cannot_manage_series`: `viewer` rep error 403.
            - `test_guest_users_cannot_manage_series`: usuaris no loguejats són redirigits al login.
            - `test_seriemanagers_can_manage_series`: `serie-manager` pot accedir a la gestió.
            - `test_superadmins_can_manage_series`: `super-admin` pot accedir a la gestió.
        - Corregits errors en els tests per usar la convención `test_` de Laravel 11 i ajustats per usar `loginAsSerieManager` en lloc de `loginAsSuperAdmin` per als tests `with_permissions`.

- **Creació de rutes per a sèries**:
    - Afegides rutes a `routes/web.php`:
        - `series/manage` per al CRUD (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`), protegides amb middleware `auth` i `role:super-admin|serie-manager`.
        - `series` per a `index` i `show`, accessibles només per a usuaris loguejats amb middleware `auth`.
    - Totes les rutes suporten navegació amb paginació.

- **Navegació entre pàgines**:
    - Implementada paginació a les vistes `series/index.blade.php` i `series/manage/index.blade.php` utilitzant el paginador de Laravel.
    - Afegits enllaços de navegació al **navbar** per accedir a sèries i vídeos, visibles només per a usuaris loguejats.

--- 

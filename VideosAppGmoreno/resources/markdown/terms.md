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

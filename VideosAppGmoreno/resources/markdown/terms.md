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

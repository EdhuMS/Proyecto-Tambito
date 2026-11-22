#!/usr/bin/env bash
set -euo pipefail
BASE=http://127.0.0.1:8766
JAR=$(mktemp)
JAR_ADMIN=$(mktemp)
trap 'rm -f "$JAR" "$JAR_ADMIN"' EXIT

get_token() {
  curl -fsS -c "$1" -b "$1" "$BASE/$2" |
    sed -n 's/.*name="csrf_token" value="\([^"]*\)".*/\1/p' | head -n 1
}
status() {
  curl -sS -c "$JAR" -b "$JAR" -o /dev/null -w '%{http_code}' "$@"
}
token=$(get_token "$JAR" registro.php)
test -n "$token"
signup_code=$(status -X POST "$BASE/guardar_usuario.php"   --data-urlencode "csrf_token=$token"   --data-urlencode "nombre=Demo"   --data-urlencode "apellido=User"   --data-urlencode "edad=22"   --data-urlencode "celular=987654321"   --data-urlencode "direccion=Direccion demo 123"   --data-urlencode "email=demo@example.test"   --data-urlencode "password=Password123456!"   --data-urlencode "confirm-password=Password123456!")
test "$signup_code" = 303

login_token=$(get_token "$JAR" login.php)
test -n "$login_token"
login_code=$(status -X POST "$BASE/validar_usuario.php"   --data-urlencode "csrf_token=$login_token"   --data-urlencode "email=demo@example.test"   --data-urlencode "password=Password123456!")
test "$login_code" = 303
test "$(status "$BASE/administracion.php")" = 302
test "$(status -X POST "$BASE/api/guardar_contacto.php")" = 403

php tests/integration_setup.php promote

admin_token=$(get_token "$JAR_ADMIN" login.php)
test -n "$admin_token"
admin_code=$(curl -sS -c "$JAR_ADMIN" -b "$JAR_ADMIN" -o /dev/null -w '%{http_code}' -X POST "$BASE/validar_usuario.php"   --data-urlencode "csrf_token=$admin_token"   --data-urlencode "email=demo@example.test"   --data-urlencode "password=Password123456!")
test "$admin_code" = 303
admin_page=$(curl -fsS -c "$JAR_ADMIN" -b "$JAR_ADMIN" "$BASE/administracion.php")
csrf=$(printf '%s\n' "$admin_page" | sed -n 's/.*name="csrf-token" content="\([^"]*\)".*/\1/p' | head -n 1)
test -n "$csrf"
blocked=$(curl -sS -c "$JAR_ADMIN" -b "$JAR_ADMIN" -o /dev/null -w '%{http_code}' -X POST "$BASE/api/guardar_contacto.php")
test "$blocked" = 403
result=$(curl -fsS -c "$JAR_ADMIN" -b "$JAR_ADMIN" -X POST "$BASE/api/guardar_contacto.php"   -H "X-CSRF-Token: $csrf"   --data-urlencode "contacto_horario=Demo"   --data-urlencode "contacto_email=demo@example.test")
printf '%s' "$result" | grep -q '"success":true'
echo "OK: MySQL registration, login, user/admin access, CSRF rejection and authorized content edit"

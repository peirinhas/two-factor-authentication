FROM nginx:latest

ARG UID
EXPOSE $UID

#creamos usuario con nuestro id usuario, sin contraseña y de nombre appuser
RUN adduser -u ${UID} --disabled-password --gecos "" appuser

COPY default.conf /etc/nginx/conf.d/

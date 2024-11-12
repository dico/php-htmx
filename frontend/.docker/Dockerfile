# Bruk nginx som base image
FROM nginx:alpine

# Kopier frontend-filer til nginx-standardkatalogen
COPY ./src /usr/share/nginx/html

# Eksponer port 80 for frontend
EXPOSE 80

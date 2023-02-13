# Vue-client Service
FROM node:latest
WORKDIR /app
COPY ./client/ /app
RUN npm install && npm run build
EXPOSE 8080
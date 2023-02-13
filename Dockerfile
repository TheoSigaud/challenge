# Vue-client Service
FROM node:latest
WORKDIR /app
COPY ./client/ /app
RUN npm install && npm run dev
EXPOSE 8080
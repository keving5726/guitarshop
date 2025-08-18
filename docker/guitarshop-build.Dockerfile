# syntax=docker/dockerfile:1

FROM node:iron-alpine AS build
RUN corepack enable
WORKDIR /app
COPY package.json yarn.lock .yarnrc.yml ./
RUN yarn install
COPY webpack.config.js ./
COPY assets assets/
RUN yarn build

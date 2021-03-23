FROM node:8.12.0-alpine

WORKDIR /pocketpilot

COPY package*.json ./

RUN npm install

COPY ./assets /pocketpilot/assets
COPY ./package.json /pocketpilot/package.json
COPY ./package-lock.json /pocketpilot/package-lock.json
COPY ./webpack.config.js /pocketpilot/webpack.config.js
COPY ./.eslintrc.js /pocketpilot/eslintrc.js

EXPOSE 3000

CMD ["npm", "run", "dev"]

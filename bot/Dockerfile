FROM node:20-alpine
WORKDIR /usr/src/app
COPY bot/package.json ./package.json
RUN npm install --omit=dev
COPY bot/index.js ./index.js
CMD ["npm", "start"]

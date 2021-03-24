FROM node:8.12.0-alpine
ARG USER_ID
ARG GROUP_ID

WORKDIR /pocketpilot

COPY /init /

RUN if [ -n "$USER_ID" ] && [ "${USER_ID}" != 0 ]; then \
    addgroup -g "${GROUP_ID}" developer && adduser -u "${USER_ID}" -G developer -D developer && \
    chown developer:developer /pocketpilot; \
    fi

USER developer

CMD ["/init"]

FROM nginx:1.12.1

ADD docker/vhost.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]


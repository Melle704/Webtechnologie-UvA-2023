FROM mattrayner/lamp:latest-2004-php8
COPY ./build_sample_db.sh /mnt
ENV MYSQL_ADMIN_PASS="mLqXRHVJ7B2c"
ENTRYPOINT ["/mnt/build_sample_db.sh"]

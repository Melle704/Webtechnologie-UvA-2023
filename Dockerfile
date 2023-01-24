FROM python:3
RUN pip install --no-cache-dir --upgrade pip && \
    pip install --no-cache-dir requests

FROM mattrayner/lamp:latest-2004-php8
COPY ./build_cards_db.py /mnt
COPY ./build_sample_db.sh /mnt
RUN chmod +x /mnt/build_cards_db.py
RUN chmod +x /mnt/build_sample_db.sh
ENV MYSQL_ADMIN_PASS="mLqXRHVJ7B2c"

ENTRYPOINT ["/mnt/build_sample_db.sh"]

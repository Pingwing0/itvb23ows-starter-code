FROM python:3

WORKDIR /ai

RUN pip3 install flask
RUN git clone https://github.com/hanze-hbo-ict/itvb23ows-hive-ai.git

EXPOSE 5000

WORKDIR /ai/itvb23ows-hive-ai

CMD [ "flask", "--app", "app", "run", "--host", "0.0.0.0", "--debug" ]
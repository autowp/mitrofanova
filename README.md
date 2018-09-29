## Build

```
sudo docker build . -t autowp/mitrofanova
```

## Check

```
composer cs-check
composer phpmd
```

## Run

```
sudo docker run -d -p 80:80 autowp/mitrofanova
```

## Live

http://mitrofanova.pereslegin.ru/
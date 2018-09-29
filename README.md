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
sudo docker run autowp/mitrofanova -p 80:80
```

## Live

http://mitrofanova.pereslegin.ru/
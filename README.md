## CMD Lab
Add the required environment variable to `docker-compose.yml`:

```yaml
environment:
  CYBER_RANGE_FLAG: ${CYBER_RANGE_FLAG:?CYBER_RANGE_FLAG env is required}
````

Update `entrypoint.sh` so that it:

* requires `CYBER_RANGE_FLAG` to exist
* writes its value into:

```text
app/public/cyber_range_flag.txt
```

### Result

* When the container starts, it will fail if `CYBER_RANGE_FLAG` is missing.
* If `CYBER_RANGE_FLAG` is provided, its value will be written to `app/public/cyber_range_flag.txt`.




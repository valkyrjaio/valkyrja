# Benchmark Results

|Framework            |Requests/Second|Memory Usage|Time/Request|Files Loaded|
|---------------------|--------------:|-----------:|-----------:|-----------:|
|CodeIgniter***       |        6060.19|      411000|    0.000595|          27|
|Valkyrja (version)*  |        5011.54|      657504|    0.001068|          85|
|Valkyrja             |        4543.57|      678184|    0.001226|          93|
|Lumen* **            |        4331.55|      710792|    0.003474|          79|
|Slim* **             |        4041.30|      599536|    0.001786|         126|
|Silex                |        2916.24|      740792|    0.002506|         146|
|Symfony              |        1865.81|     1343984|    0.004068|         312|
|Zend                 |        1823.19|     1179304|    0.003806|         204|
|Laravel              |         953.60|     2204160|    0.006106|         277|

* \* Does not render view
* \*\* Does not dispatch to controller
* \*\*\* No Composer autoloader

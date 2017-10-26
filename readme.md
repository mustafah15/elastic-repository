# Aqarmap elastic search repository

## :collision: Goals 
- provide active repository pattern over your elasticsearch indices
- provide query builder for elasticsearch 


## :sparkles: Usage 
- Extend ESRepository class as a repository for your type or index.
```php
class schoolsRepository extends ESRepository 
{
    // method contains some bussiness logic 
    public function returnSomeResultWithScoreingFun()
    {
        $this->where()->getResultWithScore();
    }
}
```
##  :clipboard: Documentation 
### todo
- queries caching support


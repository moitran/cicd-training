# CI/CD Training

[![codecov](https://codecov.io/gh/moitran/cicd-training/branch/master/graph/badge.svg?token=LWXK4X2SVC)](https://codecov.io/gh/moitran/cicd-training)

* Superheroes RESTful API is builded in Symfony 5
* Build Jenkins server on AWS EC2 - Ubuntu Focal 20.04 (LTS)
    * Installing Docker: https://docs.docker.com/engine/install/ubuntu/
    * Set up Jenkins & Mysql Database on EC2 by docker
        ```
        docker-compose up -d -f docker-compose.jenkins.yml
        ```
    * Setup Jenkins job & Credential
        * Jenkins plugins
            * GitHub Pull Request Builder
            * SSH Agent Plugin
            * Docker
        * Credential
            * Github token
            * EC2 ssh key
            * Docker Hub account
            * Codecov token
            * Database
    * Setup github webhook trigger for PR merged & pushing code to master branch
        * URL `{JENKINS_URL}/github-webhook/`
        * events: `Pushed`

### How to run Superheroes RESTful API in Local Machine?
```
git clone git@github.com:moitran/cicd-training.git
cd cicd-training
docker-compose up -d
```

Then access http://localhost/api on your machine browser. 

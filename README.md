# CI/CD Training

[![codecov](https://codecov.io/gh/moitran/cicd-training/branch/master/graph/badge.svg?token=LWXK4X2SVC)](https://codecov.io/gh/moitran/cicd-training)

* Superheroes RESTful API is builded in Symfony 5
* Build Jenkins server on AWS EC2 - Ubuntu 20.04.3 LTS (GNU/Linux 5.11.0-1022-aws x86_64)
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
<img width="1440" alt="image" src="https://user-images.githubusercontent.com/30226535/161372905-684e1e98-f806-4dd4-a327-499614cbd373.png">

### Jenkins job workflow
![CICD-training-jenkins-job-workflow](https://user-images.githubusercontent.com/30226535/161372988-e6e7176a-ddca-4765-878d-e66eecfd6dad.png)


pipeline {
    agent any

    environment {
        APP_ENV = 'dev'
        DATABASE_URL = credentials("superheros-dev-db-url")
        DOCKER_TAG="${GIT_BRANCH.tokenize('/').pop()}-${GIT_COMMIT.substring(0,7)}"
    }

    stages {
        stage('Build Code') {
            steps {
                sh 'php --version'
                sh 'composer --version'
                sh 'touch .env'
                sh 'echo APP_ENV=${APP_ENV} >> .env'
                sh 'echo DATABASE_URL=${DATABASE_URL} >> .env'
                sh 'composer install --prefer-dist --no-interaction'
                sh 'bin/console secrets:set APP_SECRET --random'
            }
        }

        stage('PHP Code Sniffer') {
            steps {
                sh 'vendor/bin/phpcs src/Service --standard=PSR2'
            }
        }

        stage('Unit Test') {
            steps {
                sh 'vendor/bin/phpunit --coverage-clover=\'reports/coverage/coverage.xml\''
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t moitran/cicd-training:${DOCKER_TAG} -f Dockerfile-build .'
                sh 'docker tag moitran/cicd-training:${DOCKER_TAG} moitran/cicd-training:latest'
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'docker-hub', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                    sh "echo $DOCKER_PASSWORD | docker login --username $DOCKER_USERNAME --password-stdin"
                    sh "docker push moitran/cicd-training:${DOCKER_TAG}"
                    sh "docker push moitran/cicd-training:latest"

                }
                //clean to save disk
                sh "docker rmi moitran/cicd-training:${DOCKER_TAG}"
                sh "docker rmi moitran/cicd-training:latest"
            }
        }
    }

    post {
        success {
            echo "SUCCESSFUL"
        }

        failure {
            echo "FAILED"
        }
    }
}

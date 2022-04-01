pipeline {
    agent any

    environment {
        APP_ENV = 'dev'
        DATABASE_URL = credentials("superheros-dev-db-url")
        DOCKER_TAG="${GIT_COMMIT.substring(0,7)}"
        DOCKER_IMAGE = 'moitran/cicd-training'
        PR_STATE = "${GITHUB_PR_STATE}"
        GIT_BRANCH = "${GIT_BRANCH}"
    }

    stages {
        stage('Build Code') {
            steps {
                sh "echo ${PR_STATE}"
                sh "echo ${GIT_BRANCH}"
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
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        sh 'docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} -f Dockerfile-build .'
                        sh 'docker tag ${DOCKER_IMAGE}:${DOCKER_TAG} ${DOCKER_IMAGE}:latest'
                    }
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    if (env.GIT_BRANCH == 'origin/master') {
                        withCredentials([usernamePassword(credentialsId: 'docker-hub', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                            sh "echo $DOCKER_PASSWORD | docker login --username $DOCKER_USERNAME --password-stdin"
                            sh "docker push ${DOCKER_IMAGE}:${DOCKER_TAG}"
                            sh "docker push ${DOCKER_IMAGE}:latest"

                        }
                        //clean to save disk
                        sh '''
                            docker rmi -f $(docker images ${DOCKER_IMAGE} -a -q)
                        '''
                    }
                }
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

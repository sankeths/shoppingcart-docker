pipeline {
  agent none
  stages {
    stage('API Tests') {
      steps {
        echo 'API testing Phase'
      }
    }
    stage('Functional Tests') {
      parallel {
        stage('Firefox Tests') {
          steps {
            sleep 10
          }
        }
        stage('Chrome Tests') {
          steps {
            sleep 10
          }
        }
      }
    }
    stage('Load Tests') {
      parallel {
        stage('Web Load Test') {
          steps {
            sleep 10
          }
        }
        stage('Load - API Test') {
          steps {
            sleep 10
          }
        }
      }
    }
    stage('Deploy') {
      steps {
        sleep 10
      }
    }
    stage('Monitor') {
      steps {
        sleep 10
      }
    }
  }
}
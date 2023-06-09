job('ejemplo-job-DSL-propio') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
  parameters {
  	stringParam('nombre', defaultValue = 'Braian', description = 'parametro de cadena para el job booleano')
  	choiceParam('planeta', ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Jupiter', 'Saturno', 'Urano', 'Neptuno'])
  	booleanParam('Agente', false)
  }
  triggers {
  	cron('H/7 * * * *')
  }
  steps {
  	shell("bash jobscript.sh")
  }
  publishers {
  	mailer('zamudiobraianhernan@gmail.com', true, true)
  }
}
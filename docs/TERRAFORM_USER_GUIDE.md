# SMS Terraform Infrastructure - Complete User Guide

## 🚀 Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Installation & Setup](#installation--setup)
4. [Configuration](#configuration)
5. [Deployment Guide](#deployment-guide)
6. [Environment Management](#environment-management)
7. [Monitoring & Observability](#monitoring--observability)
8. [Maintenance & Updates](#maintenance--updates)
9. [Troubleshooting](#troubleshooting)
10. [Advanced Usage](#advanced-usage)
11. [Security Best Practices](#security-best-practices)
12. [FAQ](#faq)

---

## Overview

The SMS (School Management System) Terraform infrastructure provides a complete Infrastructure as Code (IaC) solution for deploying and managing the SMS application on Kubernetes. This guide covers everything from initial setup to advanced operations.

### Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    SMS Kubernetes Cluster                   │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐       │
│  │ SMS API     │  │ Academic    │  │ Teacher     │       │
│  │ Gateway     │  │ Service     │  │ Service     │       │
│  └─────────────┘  └─────────────┘  └─────────────┘       │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐       │
│  │ MySQL       │  │ Prometheus  │  │ Grafana     │       │
│  │ Database    │  │ Monitoring  │  │ Dashboard   │       │
│  └─────────────┘  └─────────────┘  └─────────────┘       │
└─────────────────────────────────────────────────────────────┘
```

### Components Deployed

- **SMS API Gateway**: Main REST API service
- **Academic Service**: Student and academic management
- **Teacher Service**: Teacher and staff management
- **MySQL Database**: Persistent data storage
- **Monitoring Stack**: Prometheus, Grafana, Alertmanager
- **Ingress Controller**: External access management

---

## Prerequisites

### System Requirements

- **Operating System**: Linux (Ubuntu 18.04+, CentOS 7+)
- **Memory**: Minimum 8GB RAM (16GB recommended)
- **Storage**: 50GB available disk space
- **Network**: Internet connectivity for downloading dependencies

### Required Tools

#### 1. Terraform
```bash
# Install via Snap (recommended)
sudo snap install terraform --classic

# Verify installation
terraform version
```

#### 2. kubectl
```bash
# Download and install kubectl
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
chmod +x kubectl
sudo mv kubectl /usr/local/bin/

# Verify installation
kubectl version --client
```

#### 3. Helm
```bash
# Install Helm
curl https://baltocdn.com/helm/signing.asc | sudo apt-key add -
echo "deb https://baltocdn.com/helm/stable/debian/ all main" | sudo tee /etc/apt/sources.list.d/helm-stable-debian.list
sudo apt-get update && sudo apt-get install helm

# Verify installation
helm version
```

#### 4. Docker (for building images)
```bash
# Install Docker
sudo apt-get update
sudo apt-get install docker.io
sudo systemctl start docker
sudo systemctl enable docker
sudo usermod -aG docker $USER

# Verify installation
docker version
```

### Kubernetes Cluster

You need a running Kubernetes cluster. Options include:

- **Minikube** (development)
- **Kind** (local testing)
- **K3s** (lightweight production)
- **Cloud providers** (EKS, GKE, AKS)

---

## Installation & Setup

### 1. Clone the Repository

```bash
cd /home/wk
git clone https://github.com/BlacMeW/sms_dev.git
cd sms_dev/terraform
```

### 2. Initialize Terraform

```bash
# Initialize the main configuration
terraform init

# Verify initialization
terraform validate
```

### 3. Setup Environment

```bash
# Create configuration from template
cp terraform.tfvars.example terraform.tfvars

# Edit configuration (see Configuration section)
nano terraform.tfvars
```

---

## Configuration

### Main Configuration File: `terraform.tfvars`

```hcl
#################################
# Kubernetes Configuration
#################################
kubeconfig_path    = "/home/wk/.kube/config"
kubeconfig_context = null  # Use default context

#################################
# Environment Settings
#################################
environment = "production"
namespace   = "sms-app"
app_version = "latest"
domain_name = "sms.yourdomain.com"

#################################
# Security Configuration
# IMPORTANT: Change these values!
#################################
mysql_root_password   = "SuperSecure123!"
grafana_admin_password = "GrafanaAdmin456!"
app_key = "base64:your-laravel-app-key-here"

#################################
# Database Configuration
#################################
mysql_storage_size = "20Gi"

#################################
# Application Scaling
#################################
sms_api_replicas          = 2
academic_service_replicas = 2
teacher_service_replicas  = 2

#################################
# Resource Allocation
#################################
app_cpu_request    = "200m"
app_cpu_limit      = "1000m"
app_memory_request = "512Mi"
app_memory_limit   = "1Gi"

#################################
# Monitoring Configuration
#################################
enable_monitoring = true
```

### Configuration Parameters Explained

| Parameter | Description | Default | Required |
|-----------|-------------|---------|----------|
| `kubeconfig_path` | Path to Kubernetes config file | `/home/wk/.kube/config` | Yes |
| `environment` | Environment name | `production` | Yes |
| `namespace` | Kubernetes namespace | `sms-app` | Yes |
| `app_version` | Docker image tag | `latest` | Yes |
| `domain_name` | Domain for ingress | `sms-api.local` | Yes |
| `mysql_root_password` | MySQL root password | - | Yes |
| `mysql_storage_size` | Database storage size | `10Gi` | No |
| `*_replicas` | Number of pod replicas | `1` | No |
| `app_cpu_*` | CPU resource limits | `100m/500m` | No |
| `app_memory_*` | Memory resource limits | `256Mi/512Mi` | No |

---

## Deployment Guide

### Quick Deployment

```bash
# Navigate to terraform directory
cd /home/wk/sms_dev/terraform

# Using Makefile (recommended)
make plan    # Review deployment plan
make apply   # Deploy infrastructure

# Or using Terraform directly
terraform plan -var-file=terraform.tfvars
terraform apply -var-file=terraform.tfvars
```

### Step-by-Step Deployment

#### 1. Pre-deployment Checks

```bash
# Check Kubernetes connectivity
kubectl cluster-info

# Verify Docker images are available
docker images | grep -E "sms-api|academic|teacher"

# Validate Terraform configuration
make validate
```

#### 2. Build Docker Images (if needed)

```bash
# Build all application images
make build-images

# Or build individually
cd ../sms_app/api && docker build -t sms-api:latest .
cd ../academic && docker build -t academic-service:latest .
cd ../teacher && docker build -t teacher-service:latest .
```

#### 3. Plan Deployment

```bash
# Review what will be created
make plan

# Check for any errors or warnings
terraform plan -detailed-exitcode
```

#### 4. Deploy Infrastructure

```bash
# Deploy with confirmation
make apply

# Or auto-approve (use with caution)
terraform apply -auto-approve -var-file=terraform.tfvars
```

#### 5. Verify Deployment

```bash
# Check deployment status
make status

# Run health checks
make health-check

# Check all pods are running
kubectl get pods -n sms-app
```

### Post-Deployment Tasks

#### 1. Database Migration

```bash
# Run database migrations
make db-migrate

# Or manually
kubectl exec -n sms-app deployment/sms-api -- php artisan migrate
kubectl exec -n sms-app deployment/academic -- php artisan migrate
kubectl exec -n sms-app deployment/teacher -- php artisan migrate
```

#### 2. Access Services

```bash
# Set up port forwarding for local access
make port-forward

# Access services
# SMS API: http://localhost:8080
# Grafana: http://localhost:3000
# Prometheus: http://localhost:9090
```

---

## Environment Management

### Production Environment

```bash
cd environments/production

# Initialize production environment
terraform init

# Configure production settings
cp terraform.tfvars.example terraform.tfvars
# Edit with production values

# Deploy to production
terraform plan -var-file=terraform.tfvars
terraform apply -var-file=terraform.tfvars
```

### Staging Environment

```bash
cd environments/staging

# Initialize staging environment
terraform init

# Configure staging settings
cp terraform.tfvars.example terraform.tfvars
# Edit with staging values

# Deploy to staging
terraform plan -var-file=terraform.tfvars
terraform apply -var-file=terraform.tfvars
```

### Environment-Specific Makefile Commands

```bash
# Production
make prod-init
make prod-plan
make prod-apply

# Staging
make staging-init
make staging-plan
make staging-apply
```

---

## Monitoring & Observability

### Grafana Dashboard

Access Grafana at `http://localhost:3000` (after port-forwarding):

- **Username**: `admin`
- **Password**: Your configured `grafana_admin_password`

#### Pre-configured Dashboards

1. **Kubernetes Cluster Monitoring**
   - Node resource usage
   - Pod health and status
   - Network metrics

2. **Pod Monitoring**
   - Application-specific metrics
   - Resource consumption
   - Error rates

3. **Node Exporter**
   - System-level metrics
   - Hardware monitoring
   - Performance indicators

### Prometheus Metrics

Access Prometheus at `http://localhost:9090`:

#### Key Metrics to Monitor

```promql
# CPU Usage
rate(container_cpu_usage_seconds_total[5m])

# Memory Usage
container_memory_usage_bytes / container_spec_memory_limit_bytes

# Pod Restart Count
kube_pod_container_status_restarts_total

# HTTP Request Rate
rate(http_requests_total[5m])

# Database Connections
mysql_global_status_threads_connected
```

### Alertmanager

Configure alerts in `modules/monitoring/main.tf`:

```yaml
# Example alert rules
groups:
- name: sms-app-alerts
  rules:
  - alert: PodCrashLooping
    expr: rate(kube_pod_container_status_restarts_total[15m]) > 0
    for: 0m
    labels:
      severity: critical
    annotations:
      summary: Pod {{ $labels.pod }} is crash looping
```

### Custom Monitoring

#### Application Logs

```bash
# View application logs
make logs

# Or specific services
kubectl logs -n sms-app -l app=sms-api -f
kubectl logs -n sms-app -l app=academic -f
kubectl logs -n sms-app -l app=teacher -f
```

#### Health Checks

```bash
# Run comprehensive health check
make health-check

# Check specific endpoints
kubectl exec -n sms-app deployment/sms-api -- curl -f http://localhost/health
```

---

## Maintenance & Updates

### Updating Application Version

```bash
# Update version in terraform.tfvars
app_version = "v1.2.0"

# Apply changes
make apply
```

### Scaling Applications

```bash
# Update replica counts in terraform.tfvars
sms_api_replicas = 3
academic_service_replicas = 2

# Apply scaling changes
terraform apply -target=module.sms_app
```

### Database Maintenance

#### Backup Database

```bash
# Create database backup
kubectl exec -n sms-app deployment/mysql -- mysqldump -u root -p$MYSQL_ROOT_PASSWORD --all-databases > backup-$(date +%Y%m%d).sql
```

#### Restore Database

```bash
# Restore from backup
kubectl exec -i -n sms-app deployment/mysql -- mysql -u root -p$MYSQL_ROOT_PASSWORD < backup-20250927.sql
```

### Infrastructure Updates

#### Update Terraform Providers

```bash
# Update provider versions
terraform init -upgrade

# Apply provider updates
terraform apply -refresh-only
```

#### Update Monitoring Stack

```bash
# Update Helm charts
helm repo update
terraform apply -target=module.monitoring
```

---

## Troubleshooting

### Common Issues

#### 1. Pod Stuck in Pending State

**Symptoms**: Pods not starting, stuck in "Pending" status

**Diagnosis**:
```bash
kubectl describe pod -n sms-app <pod-name>
kubectl get events -n sms-app --sort-by=.metadata.creationTimestamp
```

**Solutions**:
- Check resource requests vs available cluster resources
- Verify persistent volume claims
- Check node taints and tolerations

#### 2. Database Connection Issues

**Symptoms**: Applications can't connect to MySQL

**Diagnosis**:
```bash
kubectl logs -n sms-app -l app=mysql
kubectl exec -n sms-app deployment/mysql -- mysql -u root -p$MYSQL_ROOT_PASSWORD -e "SELECT 1"
```

**Solutions**:
- Verify MySQL service is running
- Check database credentials in secrets
- Ensure database initialization completed

#### 3. Service Not Accessible

**Symptoms**: Cannot access services externally

**Diagnosis**:
```bash
kubectl get ingress -n sms-app
kubectl describe ingress -n sms-app sms-api-ingress
```

**Solutions**:
- Check ingress controller is installed
- Verify DNS configuration
- Check TLS certificate status

#### 4. High Resource Usage

**Symptoms**: Pods being killed (OOMKilled)

**Diagnosis**:
```bash
kubectl top pods -n sms-app
kubectl describe pod -n sms-app <pod-name>
```

**Solutions**:
- Increase memory limits in terraform.tfvars
- Optimize application code
- Add horizontal pod autoscaling

### Emergency Procedures

#### Emergency Stop

```bash
# Scale down all deployments
make emergency-stop
```

#### Emergency Start

```bash
# Scale up all deployments
make emergency-start
```

#### Complete Rollback

```bash
# Rollback to previous deployment
kubectl rollout undo deployment/sms-api -n sms-app
kubectl rollout undo deployment/academic -n sms-app
kubectl rollout undo deployment/teacher -n sms-app
```

### Debug Commands

```bash
# Check cluster status
kubectl cluster-info
kubectl get nodes
kubectl get namespaces

# Check resources
kubectl get all -n sms-app
kubectl get pv,pvc -n sms-app
kubectl get secrets,configmaps -n sms-app

# Check logs
kubectl logs -n sms-app deployment/sms-api --previous
kubectl logs -n kube-system -l k8s-app=kube-dns

# Check networking
kubectl exec -n sms-app deployment/sms-api -- nslookup mysql-service
kubectl exec -n sms-app deployment/sms-api -- curl -I http://academic-service
```

---

## Advanced Usage

### Custom Resource Definitions

#### Horizontal Pod Autoscaler

```yaml
# Create HPA for SMS API
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: sms-api-hpa
  namespace: sms-app
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: sms-api
  minReplicas: 2
  maxReplicas: 10
  metrics:
  - type: Resource
    resource:
      name: cpu
      target:
        type: Utilization
        averageUtilization: 70
```

#### Network Policies

```yaml
# Restrict database access
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: mysql-network-policy
  namespace: sms-app
spec:
  podSelector:
    matchLabels:
      app: mysql
  policyTypes:
  - Ingress
  ingress:
  - from:
    - podSelector:
        matchLabels:
          app: sms-api
    - podSelector:
        matchLabels:
          app: academic
    - podSelector:
        matchLabels:
          app: teacher
```

### CI/CD Integration

#### GitLab CI Example

```yaml
# .gitlab-ci.yml
stages:
  - validate
  - plan
  - deploy

terraform_validate:
  stage: validate
  script:
    - cd terraform
    - terraform init
    - terraform validate

terraform_plan:
  stage: plan
  script:
    - cd terraform
    - terraform plan -var-file=terraform.tfvars

terraform_apply:
  stage: deploy
  script:
    - cd terraform
    - terraform apply -auto-approve -var-file=terraform.tfvars
  only:
    - main
```

### Multi-Environment Setup

#### Workspace Management

```bash
# Create workspaces
terraform workspace new production
terraform workspace new staging
terraform workspace new development

# Switch workspaces
terraform workspace select production
terraform apply -var-file=production.tfvars

terraform workspace select staging
terraform apply -var-file=staging.tfvars
```

---

## Security Best Practices

### 1. Secrets Management

```bash
# Never commit secrets to Git
echo "terraform.tfvars" >> .gitignore
echo "*.tfstate*" >> .gitignore

# Use Kubernetes secrets
kubectl create secret generic app-secrets \
  --from-literal=mysql-password=SuperSecure123! \
  --from-literal=app-key=base64:your-key-here \
  -n sms-app
```

### 2. Network Security

```yaml
# Enable network policies
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: default-deny-all
  namespace: sms-app
spec:
  podSelector: {}
  policyTypes:
  - Ingress
  - Egress
```

### 3. RBAC Configuration

```yaml
# Create service account with limited permissions
apiVersion: v1
kind: ServiceAccount
metadata:
  name: sms-app-sa
  namespace: sms-app
---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  name: sms-app-role
  namespace: sms-app
rules:
- apiGroups: [""]
  resources: ["pods", "services", "configmaps"]
  verbs: ["get", "list", "watch"]
```

### 4. Resource Limits

```yaml
# Enforce resource quotas
apiVersion: v1
kind: ResourceQuota
metadata:
  name: sms-app-quota
  namespace: sms-app
spec:
  hard:
    requests.cpu: "4"
    requests.memory: 8Gi
    limits.cpu: "8"
    limits.memory: 16Gi
    persistentvolumeclaims: "4"
```

### 5. Pod Security Standards

```yaml
# Pod security policy
apiVersion: policy/v1beta1
kind: PodSecurityPolicy
metadata:
  name: sms-app-psp
spec:
  privileged: false
  allowPrivilegeEscalation: false
  runAsUser:
    rule: 'MustRunAsNonRoot'
  fsGroup:
    rule: 'RunAsAny'
```

---

## FAQ

### General Questions

**Q: Can I deploy to multiple clusters?**
A: Yes, use different kubeconfig files and terraform workspaces for each cluster.

**Q: How do I backup the entire infrastructure?**
A: Use `terraform state pull > backup.tfstate` and backup your terraform.tfvars file.

**Q: Can I use this with cloud providers?**
A: Yes, modify the providers.tf to include cloud-specific providers (AWS, GCP, Azure).

### Deployment Questions

**Q: What if my cluster doesn't have an ingress controller?**
A: Install NGINX ingress controller:
```bash
helm upgrade --install ingress-nginx ingress-nginx \
  --repo https://kubernetes.github.io/ingress-nginx \
  --namespace ingress-nginx --create-namespace
```

**Q: How do I change the domain after deployment?**
A: Update `domain_name` in terraform.tfvars and run `terraform apply`.

**Q: Can I deploy without monitoring?**
A: Yes, set `enable_monitoring = false` in terraform.tfvars.

### Troubleshooting Questions

**Q: Pods are in CrashLoopBackOff state**
A: Check logs with `kubectl logs -n sms-app <pod-name>` and verify environment variables and secrets.

**Q: Database migrations fail**
A: Ensure MySQL pod is ready and credentials are correct:
```bash
kubectl exec -n sms-app deployment/mysql -- mysql -u root -p$MYSQL_ROOT_PASSWORD -e "SHOW DATABASES;"
```

**Q: Monitoring stack fails to install**
A: Check if Helm is installed and you have sufficient cluster resources.

### Performance Questions

**Q: How do I optimize resource usage?**
A: Monitor with Grafana, adjust resource limits, and enable horizontal pod autoscaling.

**Q: Database is slow**
A: Increase MySQL resources, optimize queries, or consider read replicas.

---

## Support and Resources

### Documentation Links

- [Terraform Documentation](https://www.terraform.io/docs)
- [Kubernetes Documentation](https://kubernetes.io/docs)
- [Helm Documentation](https://helm.sh/docs)

### Community Support

- [Terraform Community Forum](https://discuss.hashicorp.com/c/terraform-core)
- [Kubernetes Slack](https://kubernetes.slack.com)

### Getting Help

1. Check the troubleshooting section
2. Review application and infrastructure logs
3. Consult the existing project documentation
4. Open an issue in the project repository

---

*This guide is part of the SMS Development System. For updates and contributions, please visit the project repository.*
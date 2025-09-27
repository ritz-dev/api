# SMS Terraform - Quick Reference Guide

## 🚀 Essential Commands

### Initial Setup
```bash
# Navigate to terraform directory
cd /home/wk/sms_dev/terraform

# Initialize Terraform
make init

# Create configuration
cp terraform.tfvars.example terraform.tfvars
# Edit terraform.tfvars with your values

# Validate configuration
make validate
```

### Deployment Commands
```bash
# Plan deployment (review changes)
make plan

# Deploy infrastructure
make apply

# Check deployment status
make status

# Run health checks
make health-check
```

### Environment-Specific Deployment
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

### Monitoring & Debugging
```bash
# View application logs
make logs

# Set up port forwarding for local access
make port-forward

# Run health checks
make health-check

# Check Kubernetes resources
kubectl get all -n sms-app
```

### Maintenance Commands
```bash
# Build Docker images
make build-images

# Database migrations
make db-migrate

# Create backup
make backup

# Scale deployments (emergency)
make emergency-stop    # Scale to 0 replicas
make emergency-start   # Scale back up
```

---

## ⚙️ Configuration Quick Setup

### Minimum Required Configuration (`terraform.tfvars`)
```hcl
# Kubernetes
kubeconfig_path = "/home/wk/.kube/config"

# Security (CHANGE THESE!)
mysql_root_password = "YourSecurePassword123!"
grafana_admin_password = "GrafanaPassword456!"
app_key = "base64:your-laravel-app-key-here"

# Domain
domain_name = "sms.yourdomain.com"
```

### Production Configuration
```hcl
environment = "production"
namespace = "sms-app"
app_version = "latest"

# Scaling
sms_api_replicas = 3
academic_service_replicas = 2
teacher_service_replicas = 2

# Resources
app_cpu_limit = "1000m"
app_memory_limit = "1Gi"
mysql_storage_size = "50Gi"
```

---

## 🔍 Troubleshooting Quick Fixes

### Pod Issues
```bash
# Check pod status
kubectl get pods -n sms-app

# Describe problematic pod
kubectl describe pod <pod-name> -n sms-app

# Check pod logs
kubectl logs <pod-name> -n sms-app

# Restart deployment
kubectl rollout restart deployment/<deployment-name> -n sms-app
```

### Database Issues
```bash
# Check MySQL status
kubectl get pods -n sms-app -l app=mysql

# Test database connection
kubectl exec -n sms-app deployment/mysql -- mysql -u root -p$MYSQL_ROOT_PASSWORD -e "SELECT 1"

# Run migrations
kubectl exec -n sms-app deployment/sms-api -- php artisan migrate
```

### Network Issues
```bash
# Check services
kubectl get services -n sms-app

# Check ingress
kubectl get ingress -n sms-app
kubectl describe ingress sms-api-ingress -n sms-app

# Test internal connectivity
kubectl exec -n sms-app deployment/sms-api -- curl http://mysql-service:3306
```

---

## 📊 Monitoring Access

### Local Access (via Port Forward)
```bash
make port-forward

# Then access:
# SMS API: http://localhost:8080
# Grafana: http://localhost:3000 (admin / your-grafana-password)
# Prometheus: http://localhost:9090
```

### Direct Kubernetes Access
```bash
# SMS API
kubectl port-forward -n sms-app svc/sms-api-service 8080:80

# Grafana
kubectl port-forward -n monitoring svc/grafana-service 3000:3000

# Prometheus
kubectl port-forward -n monitoring svc/prometheus-service 9090:9090
```

---

## 🛡️ Security Checklist

- [ ] Changed default passwords in `terraform.tfvars`
- [ ] Generated unique Laravel app key
- [ ] Configured proper domain name
- [ ] Set resource limits appropriate for environment
- [ ] Enabled monitoring (`enable_monitoring = true`)
- [ ] Configured ingress with TLS (production)
- [ ] Regular backups configured
- [ ] Network policies enabled (advanced)

---

## 📈 Scaling Guide

### Horizontal Scaling
```hcl
# In terraform.tfvars
sms_api_replicas = 5           # Scale SMS API
academic_service_replicas = 3   # Scale Academic service
teacher_service_replicas = 3    # Scale Teacher service
```

### Vertical Scaling
```hcl
# In terraform.tfvars
app_cpu_limit = "2000m"        # Increase CPU limit
app_memory_limit = "2Gi"       # Increase memory limit
mysql_storage_size = "100Gi"   # Increase database storage
```

### Apply Scaling Changes
```bash
terraform apply -var-file=terraform.tfvars
```

---

## 🔗 Important URLs

After deployment with port-forwarding:
- **SMS API**: http://localhost:8080
- **API Documentation**: http://localhost:8080/docs
- **Grafana Dashboard**: http://localhost:3000
- **Prometheus Metrics**: http://localhost:9090
- **Alertmanager**: http://localhost:9093

---

## 📞 Getting Help

1. **Check Status**: `make health-check`
2. **View Logs**: `make logs`
3. **Check Resources**: `kubectl get all -n sms-app`
4. **Consult Full Guide**: [Terraform User Guide](TERRAFORM_USER_GUIDE.md)
5. **Emergency Recovery**: `make emergency-stop` then `make emergency-start`

---

*This is a quick reference for the SMS Terraform infrastructure. For complete documentation, see [TERRAFORM_USER_GUIDE.md](TERRAFORM_USER_GUIDE.md)*
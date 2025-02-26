`Update [25/1/2024]`
# Project:
## Start Project:
1. Change policy in `PowerShell` to allow download scripts in internet if this have valid signature in currently user
```
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```
1. Install Scoop: `Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression`
2. Install Symfony: `scoop install symfony-cli`
3. Setting Database in .env file with database type is `mysql` and database's name is `trainingproject`
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=mariadb-10.4.11&charset=utf8mb4"
```
4. Create database: `php bin/console doctrine:database:create`
5. Create Entity: `php bin/console make:entity category`
6. Make migration: `php bin/console make:migration`
7. Migrate: `php bin/console doctrine:migrations:migrate`
8. Create Controller: `php bin/console make:crud`
9. Run server: `symfony server:start`

## Make Relationship in Product with Category
1. Create Entity Product: `php bin/console make:entity product`
2. Add some field like `Name`, `Description`
3. `Add another property? => category`
4. `Field type? => relation`
5. `What class should .... => Category`
6. `Relation type?.... => ManyToOne`


# Error:
## The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue.
Add serverVersion=mariadb-10.4.11 when you see this issue.
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=mariadb-10.4.11&charset=utf8mb4"
```
## Cannot autowire service "App\EventSubscriber\SideBarSubscriber": argument "$security" of method "__construct()" has type "Symfony\Component\Security\Core\Security" but this class was not found.
*. Add service in `services.yaml`
```
services:
    App\EventSubscriber\SideBarSubscriber:
        arguments:
            $security: '@security.helper'
        tags:
            - { name: 'kernel.event_subscriber' }
```
*. Change `use Symfony\Component\Security\Core\Security` to `use Symfony\Bundle\SecurityBundle\Security`

## Case mismatch between loaded and declared class names: "App\Entity\category" vs "App\Entity\Category".
*. Review and change type in Enity
# Tip and Trick
## How to run Symfony With API
```
Open terminal: `start run.bat`
```
## Create new branch from `dev`'s branch
```
git checkout dev       # Chuyển sang nhánh dev (nếu chưa ở đó)
git pull origin dev    # Cập nhật nhánh dev mới nhất từ remote
git checkout -b ten-nhanh-moi  # Tạo nhánh mới từ dev và chuyển sang đó
git push origin ten-nhanh-moi  
```
## Dont know how to fix? -> delete cache
```
php bin/console cache:clear
```
## Add Seeder 
- Remember hide set status in task
## Debug
```
dump($exception->getMessage());
die();
```
## All Command In Symfony
`php bin/console list`
## Help to know specific command
`php bin/console help make:entity`
## Change dislay's category in product to `name` instead of `id`
In /Form/ProductType.php:
```
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, [
                'class' => category::class,
'choice_label' => 'name', //doi cho này sẽ là name thay vi id
            ])
        ;
    }
```
## Change Route in Controller
```
#[Route('/')]
final class ProductController extends AbstractController{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
```
## Add Validation In Entity
### **UNIQUE**
```
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;
```
### **NotBlank**
```
    /**
     * @Assert\NotBlank(message="Username should not be blank")
     */
    private $username;
```
### **Length**
```
/**
     * @Assert\Length(min=6, minMessage="Password must be at least 6 characters long")
     */
    private $password;
```
### **Email**
```
    /**
     * @Assert\Email(message="Please enter a valid email address")
     */
    private $email;
```
### **Regex**
```
/**
     * @Assert\Regex(
     *     pattern="/^\d{10}$/",
     *     message="Phone number should be exactly 10 digits"
     * )
     */
    private $phoneNumber;
```
### **Range**
```
/**
     * @Assert\Range(min=18, max=100, notInRangeMessage="Age must be between {{ min }} and {{ max }}.")
     */
    private $age;
```
### **CallBack**
Date Delivery is not greater than date order  
```
    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $orderDate;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $deliveryDate;

    /**
     * @Assert\Callback
     */
    public function validateDeliveryDate(ExecutionContextInterface $context, $payload)
    {
        // Kiểm tra nếu deliveryDate nhỏ hơn orderDate
        if ($this->deliveryDate < $this->orderDate) {
            $context->buildViolation('The delivery date cannot be earlier than the order date.')
                ->atPath('deliveryDate') // Chỉ ra trường bị lỗi
                ->addViolation(); // Thêm lỗi vào validate
        }
    }

``` 
## Seeder:   
1. Install Faker: `composer require fakerphp/faker --dev`
2. Create Seeder: `php bin/console make:command app:seed-users`
3. Edit Seeder Command in `src/Command/SeedUsersCommand.php`
4. Run Seeder: `php bin/console app:seed-users`
4. *Run Command: `php bin/console TEN_FILE` (bỏ chữ Command)
## Encode Password:
`php bin/console security:encode-password`
## View All Route
`php bin/console debug:router`
## Update Database
`php bin/console doctrine:schema:update --force`
# Document
## TWIG:
Trong Symfony, Twig là một template engine được tích hợp sẵn, giúp tách biệt logic ứng dụng khỏi giao diện hiển thị. Điều này cho phép bạn xây dựng các trang web một cách hiệu quả và dễ bảo trì hơn.  
link: `https://congruous-brother-204.notion.site/Note-Twig-17971ea8591f801cbeeec65cbc8d9b55`  
DEMO:
```
{% extends 'base.html.twig' %}
{% block body %}
    <h1>Danh sách bài viết</h1>
    <ul>
        {% for article in articles %}
            <li>
                <h2>{{ article.title }}</h2>
                <p>{{ article.content }}</p>
            </li>
        {% endfor %}
    </ul>
{% endblock %}
```
## FORM
Trong Symfony, Form là một thành phần quan trọng giúp bạn tạo, xử lý và tái sử dụng các biểu mẫu một cách hiệu quả. Thay vì tạo các biểu mẫu trực tiếp trong tệp template, Symfony cung cấp một hệ thống Form mạnh mẽ, cho phép bạn xây dựng các biểu mẫu phức tạp với khả năng tùy chỉnh cao.  
link: `https://soapy-cave-a36.notion.site/Project-2-17590144b88f81d29c48ddaea3fadc0d`  
***DEMO***:  
**1. Tạo một lớp FormType**  
1.1 *Sử dụng lệnh để tạo form: `php bin/console make:form ContactType`*  
1.2 *Định nghĩa các trường trong biểu mẫu*  
```
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Tên của bạn',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email của bạn',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Nội dung tin nhắn',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Gửi',
            ]);
    }
}
 ```
**2. Sử dụng Form trong Controller**
```
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Xử lý dữ liệu biểu mẫu
            $data = $form->getData();
            // ...
            return $this->redirectToRoute('contact_success');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
```
**3. Hiển thị form trong Template**
```
{# templates/contact/contact.html.twig #}
{{ form_start(form) }}
    {{ form_row(form.name) }}
    {{ form_row(form.email) }}
    {{ form_row(form.message) }}
    {{ form_row(form.save) }}
{{ form_end(form) }}
```
# Idea
## Predict percent done 1 task for entire of member in room 
- Attribute: 
    - Quantity of task done
    - Quantity of task undone
    - Quantity of task
    - Time to done in one task
    - time done/ time deadline
    - Percent of task done in 5 task lastest
- Tech: 
    - Logistic Regression: Skilearn for model
    - FlaskAPI: connect form symfony to model
- Others:
    - Create database view in symfony
    - Multithread
- View Database in SQL
```
CREATE VIEW v_for_predict AS
SELECT 
    member_id, 
    COUNT(id) AS tong_so_task, 
    COUNT(CASE WHEN finish_date IS NOT NULL THEN 1 END) AS so_task_done,
    COUNT(CASE WHEN finish_date IS NULL THEN 1 END) AS so_task_undone,
    
    -- Phần trăm thời gian hoàn thành trên tổng thời gian được giao
    ROUND(
    (SUM(CASE WHEN finish_date IS NOT NULL THEN DATEDIFF(finish_date, start_date) ELSE 0 END) 
    / NULLIF(SUM(DATEDIFF(end_date, start_date)), 0)) * 100, 2
) AS phan_tram_thoi_gian_hoan_thanh_tren_thoi_gian_duoc_giao,


    -- Phần trăm hoàn thành 5 task gần nhất
    ROUND(
        AVG(
            CASE 
                WHEN finish_date IS NOT NULL AND 
                     task_rank <= 5  -- Chỉ tính 5 task gần nhất
                THEN (DATEDIFF(finish_date, start_date) / NULLIF(DATEDIFF(end_date, start_date), 0)) * 100 
                ELSE NULL 
            END
        ), 
        2
    ) AS phan_tram_hoan_thanh_tren_duoc_giao_5_task_gan_nhat

FROM (
    -- Xác định rank cho từng task theo start_date (5 task gần nhất)
    SELECT 
        task.*, 
        ROW_NUMBER() OVER (PARTITION BY member_id ORDER BY start_date DESC) AS task_rank
    FROM task
) AS ranked_task

GROUP BY member_id;

```
`Update [16/1/2024]`
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
# Tip and Trick
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
`php bin/console make:seeder`
## Encode Password
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

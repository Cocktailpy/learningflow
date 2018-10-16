

```python
import time
```

### 最大公约数-优化版


```python
%%time

def gcd(a, b):
    if a == b:
        return a
    if a < b:
        return gcd(b, a)
    if a&1 == 0 and b&1 == 0:
        return 2*gcd(a//2,b//2)
    elif a&1 == 0 and b&1:
        return gcd(a//2, b)
    elif a&1 and b&1 == 0:
        return gcd(a, b//2)
    return gcd(b, a-b)
print(gcd(25, 50))
```

    25
    CPU times: user 0 ns, sys: 0 ns, total: 0 ns
    Wall time: 79.9 µs
    

### 随机快速排序


```python
import random
def random_quicksort(a,left,right):
    if(left<right):
        mid = random_partition(a,left,right)
        random_quicksort(a,left,mid-1)
        random_quicksort(a,mid+1,right)

def random_partition(a,left,right): 
    t = random.randint(left,right)     #生成[left,right]之间的一个随机数
    a[t],a[right] = a[right],a[t]    
    x = a[right]
    i = left-1                         #初始i指向一个空，保证0到i都小于等于 x
    for j in range(left,right):        #j用来寻找比x小的，找到就和i+1交换，保证i之前的都小于等于x
        if(a[j]<=x):
            i = i+1
            a[i],a[j] = a[j],a[i]
    a[i+1],a[right] = a[right],a[i+1]  #0到i 都小于等于x ,所以x的最终位置就是i+1
    return i+1
data = [1, 5, 10, 12, 13, 4, 5, 7, 8, 2, 9, 15, 17]
random_quicksort(data,0,len(data)-1)
for item in data:
    print(item, end=' ')
```

    1 2 4 5 5 7 8 9 10 12 13 15 17 

### 冒泡排序优化


```python
def sort_a_optimizer(data):
    l = len(data)
    lastInd = l - 1
    for i in range(l):
        issorted=True
        for j in range(lastInd):
            if data[j] > data[j+1]:
                data[j], data[j+1] = data[j+1], data[j]
                issorted=False
                lastInd = j
        if issorted:
            print("结算轮数:{}".format(i))
            break
    return data
data = [5, 8, 6, 3, 9, 2, 1, 7]
print(sort_a_optimizer(data))
```

    结算轮数:6
    [1, 2, 3, 5, 6, 7, 8, 9]
    

### 鸡尾酒排序


```python
def sort_b(data):
    l = len(data)
    llidx = 0
    lridx = l - 1
    for i in range(int(l/2)):
        issorted=True
        for j in range(llidx, lridx, 1):
            if data[j] > data[j+1]:
                data[j], data[j+1] = data[j+1], data[j]
                issorted=False
                lridx = j
        if issorted:
            break
        issorted=True
        for j in range(lridx, llidx, -1):
            if data[j] < data[j-1]:
                data[j], data[j-1] = data[j-1], data[j]
                issorted=False
                llidx = j
        if issorted:
            break
    return data
data = [5, 8, 6, 3, 9, 2, 1, 7]
print(sort_b(data))
```

    [1, 2, 3, 5, 6, 7, 8, 9]
    

### 二叉堆
什么是二叉堆？二叉堆本质上是一种完整二叉树，它分为两个类型：1.最大堆2.最小堆


```python
def forward_adjust(data):
    child_idex = len(data)-1
    parent_index = (child_idex-1) // 2
    temp = data[child_idex]
    while child_idex > 0 and temp < data[parent_index]:
        data[child_idex] = data[parent_index]
        child_idex = parent_index
        parent_index = (parent_index-1) // 2
    data[child_idex] = temp
    print(data)

def backward_adjust(data, parent_index, l):
    temp = data[parent_index]
    child_index = parent_index * 2 + 1
    while child_index < l:
        if (child_index + 1) < l and data[child_index + 1] < data[child_index]:
            child_index +=1
        if temp <= data[child_index]:
            break
        data[parent_index] = data[child_index]
        parent_index = child_index
        child_index = child_index * 2 + 1
    data[parent_index] = temp
    return data

def build_heap(data):
    l = len(data)
    for i in range((l-2) // 2, -1, -1):
        data = backward_adjust(data, i, l)
    return data
data = [7,1,3,10,5,2,8,9,6]
print(build_heap(data))
data = [1, 3, 2, 6, 5, 7, 8, 9, 10, 0]
forward_adjust(data)
```

    [1, 5, 2, 6, 7, 3, 8, 9, 10]
    [0, 1, 2, 6, 3, 7, 8, 9, 10, 5]
    

### 堆排序
第一步，把无序数组自底向上建堆的复杂度是O（n)；第二步删除堆顶的元素的复杂度O（nlogn）。所以相加才是O（nlogn)


```python
def backward_adjust_sort(data, parent_index, length):
    temp = data[parent_index]
    child_index = parent_index * 2 + 1
    while child_index < length:
        if (child_index + 1) < length and data[child_index + 1] > data[child_index]:
            child_index += 1
        if temp > data[child_index]:
            break
        data[parent_index] = data[child_index]
        parent_index = child_index
        child_index = child_index * 2 + 1
    data[parent_index] = temp
    return data

def heap_sort(data):
    length = len(data)
    for i in range((length - 2) // 2, -1, -1):
        data = backward_adjust_sort(data, i, length)
    print(data)
    for i in range(length -1, -1, -1):
        data[i], data[0] = data[0], data[i]
        data = backward_adjust_sort(data, 0, i)
    print(data)
data = [1, 3, 2, 6, 5, 7, 8, 9, 10, 0]
heap_sort(data)
```

    [10, 9, 8, 6, 5, 7, 2, 3, 1, 0]
    [0, 1, 2, 3, 5, 6, 7, 8, 9, 10]
    

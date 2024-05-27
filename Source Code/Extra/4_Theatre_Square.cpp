#include<bits/stdc++.h>
using namespace std;

int main(){
    freopen("input.txt","r",stdin);
    int m,n,a;
    cin>>m>>n>>a;
    long long cnt=((n+a-1)/a)*((m+a-1)/a);
    cout<<cnt<<endl;
    return 0;
}

#include<bits/stdc++.h>
using namespace std;

int main(){
    freopen("input.txt","r",stdin);
    int n,k;
    cin>>n>>k;
    int a[n];
    for(int i=0;i<n;i++){
        cin>>a[i];
    }
    sort(a,a+n,greater<int>());
    int abc=a[k-1];
    int i;
    for(i=0;i<n;i++){
        if(a[i]<abc||a[i]==0){
            break;
        }
    }
    cout<<i<<endl;
    return 0;
}
